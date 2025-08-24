<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Investor;
use Illuminate\Support\Facades\Mail;

class GenerateMonthlyStatementsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $investors = Investor::with('user')->get();

        foreach ($investors as $investor) {
            if (!$investor->user) {
                \Log::warning("Investor ID {$investor->id} has no associated user.");
                continue;
            }

            $result = $this->generateMonthlyStatement($investor);
            
            if (isset($result['error'])) {
                \Log::error("Failed to generate statement for investor ID: {$investor->id}. Error: " . $result['error']);
                continue;
            }

            $this->sendStatementEmail($investor, $result['file'], $result['path']);
            \Log::info("Monthly statement generated and sent for investor ID: {$investor->id}");
        }
    }

    private function generateMonthlyStatement(Investor $investor)
    {
        $user = $investor->user;

        if (!$user) {
            return ['error' => 'User not found for investor'];
        }

        $templatePath = public_path('AMWALFLOO.pdf');

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf_temp'),
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'cairo',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font_size' => 10,
            'margin_top' => 30,
            'margin_right' => 20,
            'margin_bottom' => 30,
            'margin_left' => 20,
        ]);

        $mpdf->SetDirectionality('rtl');

        if (!file_exists($templatePath)) {
            return ['error' => 'PDF template not found'];
        }

        $pageCount = $mpdf->SetSourceFile($templatePath);

        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();
        $transactions = Transaction::where('user_id', $investor->user_id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $tableRows = '';
        foreach ($transactions as $transaction) {
            $tableRows .= "
                <tr>
                    <td>{$transaction->id}</td>
                    <td>{$transaction->created_at->format('Y-m-d')}</td>
                    <td>{$transaction->type}</td>
                    <td>" . number_format($transaction->amount) . " دولار</td>
                    <td>{$transaction->description}</td>
                </tr>
            ";
        }

        $content = "
            <style>
                body { font-family: Cairo; direction: rtl; line-height: 1.5; }
                .content-wrapper { position: absolute; top: 250px; left: 20px; right: 20px; padding: 20px; max-width: 90%; margin-left: auto; margin-right: auto; }
                .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #ddd; }
                .header h2 { font-size: 16pt; margin: 0 0 10px 0; color: #333; }
                .header p { font-size: 11pt; margin: 5px 0; color: #555; }
                .table-container { width: 100%; display: flex; justify-content: center; margin: 20px 0; }
                table { width: 100%; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
                th, td { border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 10pt; }
                th { background-color: #f5f5f5; font-weight: bold; color: #333; }
                td { color: #666; }
                .footer { text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 10pt; color: #777; }
            </style>
            <div class='content-wrapper'>
                <div class='header'>
                    <h2>كشف الحساب الشهري</h2>
                    <p>الاسم: {$user->name}</p>
                    <p>رقم المستثمر: {$investor->id}</p>
                    <p>الشهر: " . now()->subMonth()->format('F Y') . "</p>
                </div>
                <div class='table-container'>
                    <table>
                        <thead>
                            <tr>
                                <th>رقم المعاملة</th>
                                <th>التاريخ</th>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>الوصف</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . ($tableRows ?: '<tr><td colspan="5">لا توجد معاملات لهذا الشهر</td></tr>') . "
                        </tbody>
                    </table>
                </div>
                <div class='footer'>
                    <p>تم إنشاء هذا الكشف بتاريخ: " . now()->format('Y-m-d') . "</p>
                </div>
            </div>
        ";

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $mpdf->ImportPage($pageNo);
            $mpdf->UseTemplate($templateId);
            if ($pageNo == 1) {
                $mpdf->WriteHTML($content);
            }
            if ($pageNo < $pageCount) {
                $mpdf->AddPage();
            }
        }

        $fileName = "monthly_statement_{$investor->user_id}_" . now()->subMonth()->format('Y_m') . ".pdf";
        $filePath = storage_path("app/public/{$fileName}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $mpdf->Output($filePath, 'F');

        return ['file' => $fileName, 'path' => $filePath];
    }

    private function sendStatementEmail($investor, string $fileName, string $filePath)
    {
        $user = $investor->user;

        Mail::send('mail.statement.blade', ['user' => $user], function ($message) use ($user, $filePath, $fileName) {
            $message->to($user->email)
                    ->subject('Your Monthly Investment Statement - ' . now()->subMonth()->format('F Y'))
                    ->attach($filePath, [
                        'as' => $fileName,
                        'mime' => 'application/pdf',
                    ]);
        });
    }
}