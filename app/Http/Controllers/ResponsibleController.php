<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Investor;
use App\Models\Responsible;
use App\Models\User;
use Illuminate\Http\Request;
use DB;


class ResponsibleController extends Controller
{
    public function index()
    {
        // First paginate, then group
        $page = Responsible::with(['employe.user', 'investor'])
            ->paginate(20);
            
        $responsibles = $page->getCollection()
            ->groupBy('employe_id')
            ->map(function ($group) {
                return [
                    'employe' => $group->first()->employe,
                    'investors' => $group->pluck('investor')
                ];
            });
        
        $employees = Employe::get();
        $investors = User::role(['investor', 'advertiser', 'manager', 'manager_adv'])->get();
    
        // Return with pagination information
        return view('backend.responsible.responsible', compact('responsibles', 'employees', 'investors', 'page'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'employe_id' => 'required',
            'investor_id' => 'required|array',
            'investor_id.*' => 'exists:users,id' // Validate against users table
        ]);

        foreach ($request->investor_id as $userId) {
            // Check if this investor is already assigned to the same employee
            $existingResponsible = Responsible::where('investor_id', $userId)
                ->where('employe_id', $request->employe_id)
                ->first();

            if (!$existingResponsible) {
                // Assign the investor to the new employee only if not already assigned
                Responsible::create([
                    'employe_id' => $request->employe_id,
                    'investor_id' => $userId,
                ]);
            }
        }

        return redirect()->back()->with('success', 'تمت إضافة المسؤولين بنجاح!');
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'employe_id' => 'required',
            'investor_id' => 'required|array',
            'investor_id.*' => 'exists:users,id'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Remove existing associations for this employee
            Responsible::where('employe_id', $id)->delete();
    
            // Create new associations
            foreach ($request->investor_id as $investorId) {
                Responsible::create([
                    'employe_id' => $id,
                    'investor_id' => $investorId,
                ]);
            }
    
            DB::commit();
            return redirect()->back()->with('success', 'تم تعديل المسؤول بنجاح!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث!');
        }
    }



    public function destroy($id)
    {
        $responsible = Responsible::findOrFail($id);


        // Delete responsible
        $responsible->delete();

        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Carousel deleted successfully!',
        ]);
    }
}