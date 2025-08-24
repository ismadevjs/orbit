<?php

namespace App\Http\Controllers;

use App\Models\Incentive;
use App\Models\AffiliateStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class IncentiveController extends Controller
{
    /**
     * Display the incentives management view.
     */
    public function index(Request $request)
    {
        // Optional: Filter incentives by affiliate_stage_id
        $affiliateStageId = $request->query('affiliate_stage_id');

        return view('backend.incentives.incentives', compact('affiliateStageId'));
    }


    /**
     * Fetch all incentives for DataTables.
     */
    public function apiIndex()
    {
        $incentives = Incentive::with('affiliateStage')->select('incentives.*');

        return DataTables::of($incentives)
            ->addIndexColumn()
            ->addColumn('affiliate_stage', function ($incentive) {
                return $incentive->affiliateStage ? $incentive->affiliateStage->name : 'غير مرتبط';
            })
            ->addColumn('from_date', function ($incentive) {
                return $incentive->from_date ? $incentive->from_date->format('Y-m-d') : '-';
            })
            ->addColumn('to_date', function ($incentive) {
                return $incentive->to_date ? $incentive->to_date->format('Y-m-d') : '-';
            })
            ->addColumn('actions', function ($incentive) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-incentive-btn" data-id="' . $incentive->id . '"><i class="fas fa-pen"></i></button>
                    <button type="button" class="btn btn-danger btn-sm delete-incentive-btn" data-id="' . $incentive->id . '"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Store a newly created incentive in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules based on bonus type
        $rules = [
            'affiliate_stage_id' => ['required', 'exists:affiliate_stages,id'],
            'bonus_type' => ['required', 'in:yearly,monthly'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'can_invite' => ['nullable', 'boolean'],
        ];
    
        // If bonus is monthly, add date fields as required
        if ($request->bonus_type === 'monthly') {
            $rules['from_date'] = ['required', 'date'];
            $rules['to_date'] = ['required', 'date', 'after_or_equal:from_date'];
        }
    
        // Custom error messages
        $messages = [
            'affiliate_stage_id.required' => 'مستوى التسويق مطلوب.',
            'affiliate_stage_id.exists' => 'مستوى التسويق غير موجود.',
            
            'bonus_type.required' => 'نوع البونص مطلوب.',
            'bonus_type.in' => 'نوع البونص غير صالح.',
            'percentage.required' => 'النسبة المئوية مطلوبة.',
            'percentage.numeric' => 'النسبة المئوية يجب أن تكون رقمًا.',
            'percentage.min' => 'النسبة المئوية يجب ألا تقل عن 0.',
            'percentage.max' => 'النسبة المئوية يجب ألا تتجاوز 100.',
            'can_invite.boolean' => 'قيمة يمكن الدعوة يجب أن تكون صحيحة أو خاطئة.',
            'from_date.required' => 'من تاريخ مطلوب للبونص الشهري.',
            'from_date.date' => 'من تاريخ يجب أن يكون تاريخ صالح.',
            'to_date.required' => 'إلى تاريخ مطلوب للبونص الشهري.',
            'to_date.date' => 'إلى تاريخ يجب أن يكون تاريخ صالح.',
            'to_date.after_or_equal' => 'إلى تاريخ يجب أن يكون بعد أو يساوي من تاريخ.',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Handle validation failures
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Prepare data for creation
        $data = [
            'affiliate_stage_id' => $request->affiliate_stage_id,
            'bonus_type' => $request->bonus_type,
            'percentage' => $request->percentage,
            'can_invite' => $request->can_invite ?? false, // Default to false if null
        ];
    
        // If bonus is monthly, include date fields
        if ($request->bonus_type === 'monthly') {
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
        } else {
            $data['from_date'] = null;
            $data['to_date'] = null;
        }
    
        // Create Incentive
        $incentive = Incentive::create($data);
        return response()->json(['success' => true, 'message' => 'تم إنشاء الحافز بنجاح!', 'incentive' => $incentive]);
    }
    

    /**
     * Display the specified incentive.
     */
    public function show($id)
    {
        $incentive = Incentive::with('affiliateStage')->findOrFail($id);
    
        return response()->json([
            'id' => $incentive->id,
            'affiliate_stage_id' => $incentive->affiliate_stage_id,
            'bonus_type' => $incentive->bonus_type,
            'from_date' => $incentive->from_date ? $incentive->from_date->format('Y-m-d') : null, // Format the date
            'to_date' => $incentive->to_date ? $incentive->to_date->format('Y-m-d') : null,       // Format the date
            'percentage' => $incentive->percentage,
            'can_invite' => $incentive->can_invite,
            'affiliate_stage' => $incentive->affiliateStage, // Include related data
        ]);
    }
    

    /**
     * Update the specified incentive in storage.
     */
    public function update(Request $request, $id)
    {
        $incentive = Incentive::findOrFail($id);
    
        // Define validation rules based on bonus type
        $rules = [
            'affiliate_stage_id' => ['required', 'exists:affiliate_stages,id'],
            'bonus_type' => ['required', 'in:yearly,monthly'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'can_invite' => ['nullable', 'boolean'],
        ];
    
        // If bonus is monthly, add date fields as required
        if ($request->bonus_type === 'monthly') {
            $rules['from_date'] = ['required', 'date'];
            $rules['to_date'] = ['required', 'date', 'after_or_equal:from_date'];
        }
    
        // Custom error messages
        $messages = [
            'affiliate_stage_id.required' => 'مستوى التسويق مطلوب.',
            'affiliate_stage_id.exists' => 'مستوى التسويق غير موجود.',
            
            'bonus_type.required' => 'نوع البونص مطلوب.',
            'bonus_type.in' => 'نوع البونص غير صالح.',
            'percentage.required' => 'النسبة المئوية مطلوبة.',
            'percentage.numeric' => 'النسبة المئوية يجب أن تكون رقمًا.',
            'percentage.min' => 'النسبة المئوية يجب ألا تقل عن 0.',
            'percentage.max' => 'النسبة المئوية يجب ألا تتجاوز 100.',
            'can_invite.boolean' => 'قيمة يمكن الدعوة يجب أن تكون صحيحة أو خاطئة.',
            'from_date.required' => 'من تاريخ مطلوب للبونص الشهري.',
            'from_date.date' => 'من تاريخ يجب أن يكون تاريخ صالح.',
            'to_date.required' => 'إلى تاريخ مطلوب للبونص الشهري.',
            'to_date.date' => 'إلى تاريخ يجب أن يكون تاريخ صالح.',
            'to_date.after_or_equal' => 'إلى تاريخ يجب أن يكون بعد أو يساوي من تاريخ.',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Handle validation failures
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Prepare data for update
        $data = [
            'affiliate_stage_id' => $request->affiliate_stage_id,
            'bonus_type' => $request->bonus_type,
            'percentage' => $request->percentage,
            'can_invite' => $request->can_invite ?? $incentive->can_invite, // Retain existing value if not provided
        ];
    
        // If bonus is monthly, include date fields
        if ($request->bonus_type === 'monthly') {
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
        } else {
            $data['from_date'] = null;
            $data['to_date'] = null;
        }
    
        // Update Incentive
        $incentive->update($data);
    
        return response()->json(['success' => true, 'message' => 'تم تحديث الحافز بنجاح!', 'incentive' => $incentive]);
    }
    

    /**
     * Remove the specified incentive from storage.
     */
    public function destroy($id)
    {
        $incentive = Incentive::findOrFail($id);
        $incentive->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الحافز بنجاح!']);
    }
}
