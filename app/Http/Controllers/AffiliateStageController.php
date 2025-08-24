<?php

namespace App\Http\Controllers;

use App\Models\AffiliateStage;
use App\Models\Tag;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AffiliateStageController extends Controller
{
    /**
     * Display the affiliate stages management view.
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['admin', 'employee'])->get();
        return view('backend.affiliates.affiliates', compact('roles'));
    }

    /**
     * Store a newly created affiliate stage in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'capital' => ['required', 'numeric', 'min:0'],
            'team_size' => ['required', 'integer', 'min:0'],
            'people_per_six_months' => ['required', 'integer', 'min:0'],
            'role_id' => ['required', 'exists:roles,id'],
            'contract_id' => ['required', 'exists:contracts,id'],
            'commission_percentage' => ['required', 'numeric', 'min:0'],
            'monthly_profit_less_50k' => ['required', 'numeric', 'min:0'],
            'monthly_profit_more_50k' => ['required', 'numeric', 'min:0'],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'duration.required' => 'مدة العمل مطلوبة.',
            'capital.required' => 'رأس المال مطلوب.',
            'team_size.required' => 'عدد أفراد الفريق مطلوب.',
            'people_per_six_months.required' => 'عدد الأشخاص الذي يجب جلبهم كل 6 أشهر مطلوب.',
            'role_id.required' => 'الرتبة مطلوبة.',
            'contract_id.required' => 'نوع العقد مطلوب.',
            'commission_percentage.required' => 'نسبة العمولة مطلوبة.',
            'commission_percentage.numeric' => 'نسبة العمولة يجب أن تكون رقمية.',
            'commission_percentage.min' => 'نسبة العمولة يجب أن تكون 0 أو أكثر.',
            'monthly_profit_less_50k.required' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف مطلوبة.',
            'monthly_profit_less_50k.numeric' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف يجب أن تكون رقمية.',
            'monthly_profit_less_50k.min' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف يجب أن تكون 0 أو أكثر.',
            'monthly_profit_more_50k.required' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف مطلوبة.',
            'monthly_profit_more_50k.numeric' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف يجب أن تكون رقمية.',
            'monthly_profit_more_50k.min' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف يجب أن تكون 0 أو أكثر.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create Affiliate Stage
        $stage = AffiliateStage::create([
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'capital' => $request->capital,
            'team_size' => $request->team_size,
            'people_per_six_months' => $request->people_per_six_months,
            'role_id' => $request->role_id,
            'contract_id' => $request->contract_id,'commission_percentage' => $request->commission_percentage,
            'monthly_profit_less_50k' => $request->monthly_profit_less_50k,
            'monthly_profit_more_50k' => $request->monthly_profit_more_50k,
        ]);

        return response()->json(['success' => true, 'message' => 'تم إنشاء مستوى التسويق بنجاح!', 'stage' => $stage]);
    }


    /**
     * Update the specified affiliate stage in storage.
     */
    public function update(Request $request, $id)
    {
        $stage = AffiliateStage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'capital' => ['required', 'numeric', 'min:0'],
            'team_size' => ['required', 'integer', 'min:0'],
            'people_per_six_months' => ['required', 'integer', 'min:0'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'contract_id' => ['required', 'integer', 'exists:contracts,id'],
            'commission_percentage' => ['required', 'numeric', 'min:0'],
            'monthly_profit_less_50k' => ['required', 'numeric', 'min:0'],
            'monthly_profit_more_50k' => ['required', 'numeric', 'min:0'],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'duration.required' => 'مدة العمل مطلوبة.',
            'capital.required' => 'رأس المال مطلوب.',
            'team_size.required' => 'عدد أفراد الفريق مطلوب.',
            'people_per_six_months.required' => 'عدد الأشخاص الذي يجب جلبهم كل 6 أشهر مطلوب.',
            'role_id.required' => 'الوظيفة مطلوبة.',
            'contract_id.required' => 'نوع العقد مطلوب.',
            'commission_percentage.required' => 'نسبة العمولة مطلوبة.',
            'commission_percentage.numeric' => 'نسبة العمولة يجب أن تكون رقمية.',
            'commission_percentage.min' => 'نسبة العمولة يجب أن تكون 0 أو أكثر.',
            'monthly_profit_less_50k.required' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف مطلوبة.',
            'monthly_profit_less_50k.numeric' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف يجب أن تكون رقمية.',
            'monthly_profit_less_50k.min' => 'نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف يجب أن تكون 0 أو أكثر.',
            'monthly_profit_more_50k.required' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف مطلوبة.',
            'monthly_profit_more_50k.numeric' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف يجب أن تكون رقمية.',
            'monthly_profit_more_50k.min' => 'نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف يجب أن تكون 0 أو أكثر.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update Affiliate Stage
        $stage->update([
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'capital' => $request->capital,
            'team_size' => $request->team_size,
            'people_per_six_months' => $request->people_per_six_months,
            'role_id' => $request->role_id,
            'contract_id' => $request->contract_id,
            'commission_percentage' => $request->commission_percentage,
            'monthly_profit_less_50k' => $request->monthly_profit_less_50k,
            'monthly_profit_more_50k' => $request->monthly_profit_more_50k,
        ]);

        return response()->json(['success' => true, 'message' => 'تم تحديث مستوى التسويق بنجاح!', 'stage' => $stage]);
    }

    /**
     * Remove the specified affiliate stage from storage.
     */
    public function destroy($id)
    {
        $stage = AffiliateStage::findOrFail($id);
        $stage->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف مستوى التسويق بنجاح!']);
    }

    /**
     * Fetch all affiliate stages for DataTables.
     */
    public function apiIndex()
    {
        return DataTables::of(AffiliateStage::query())
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                return $row->role ? $row->role->name : 'N/A';
            })
            ->addColumn('contract', function ($row) {
                return $row->contract ? $row->contract->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        $affiliate = AffiliateStage::findOrFail($id);
        return response()->json($affiliate);
    }

}
