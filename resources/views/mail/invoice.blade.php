<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f9f9f9;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 10px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background: #f5f5f5;
            padding: 10px;
            text-align: right;
            border-bottom: 2px solid #ddd;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }
        .total {
            text-align: left;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .total strong {
            color: #2c3e50;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>فاتورة</h1>
            <p>رقم الفاتورة #{{ $invoice_number }}</p>
            <p>التاريخ: {{ $date }}</p>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <strong>مفوتر إلى:</strong><br>
                    {{ $user['name'] }}<br>
                    {{ $user['email'] }}<br>
                    {{ $user['address'] }}
                </td>
                <td>
                    <strong>من:</strong><br>
                    أموال فلو<br>
                    info@amwalflow.com<br>
                    برج باينري، دبي، الإمارات العربية المتحدة
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ number_format($item['amount'], 2) }} {{ $item['currency'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>المجموع الفرعي: {{ number_format($subtotal, 2) }} {{ $transaction['currency'] }}</p>
            <p>الضريبة: {{ number_format($tax, 2) }} {{ $transaction['currency'] }}</p>
            <strong>الإجمالي: {{ number_format($total, 2) }} {{ $transaction['currency'] }}</strong>
        </div>

        <div class="footer">
            <p>شكرًا لتعاملكم معنا!</p>
            <p>إذا كانت لديكم أي استفسارات، يرجى التواصل معنا على info@amwalflow.com</p>
        </div>
    </div>
</body>
</html>