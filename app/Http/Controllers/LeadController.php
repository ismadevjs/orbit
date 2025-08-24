<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

// Ensure this model exists

class LeadController extends Controller
{
    public function index()
    {
        return view('backend.leads.leads'); // Adjust the view name accordingly
    }

    public function store(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'zip' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }


        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['zip'] = $request->zip;
        $data['phone'] = $request->phone;
        $data['source'] = $request->source;
        $data['budget'] = $request->budget;
        $data['type'] = $request->type;
        $data['user_id'] = Auth::id();

        $lead = Lead::create($data);


        sendNotification(Auth::id(), 'info', 'Leads Notification', 'You created this lead #lead' . $lead->first_name . ' ' . $lead->last_name);
        User::role('admin')->chunk(100, function ($admins) use ($lead) {
            foreach ($admins as $admin) {
                sendNotification($admin->id, 'info', 'Leads Notification', 'The user : ' . Auth::user()->name . ' has created a lead: ' . $lead->first_name . ' ' . $lead->last_name);
            }
        });

        return back()->withSuccess('Lead created successfully!');
    }

    public function storeWebsite(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        // Split the name into first and last name
        $nameParts = explode(' ', $request->name, 2); // Split into at most 2 parts
        $data['first_name'] = $nameParts[0]; // The first part is the first name
        $data['last_name'] = $nameParts[1] ?? ''; // The second part is the last name or empty if not provided

        $data['email'] = $request->email;
        $data['source'] = 'website';
        $data['zip'] = '';
        $data['budget'] = '';
        $data['type'] = $request->type;

        $lead = Lead::create($data);

        return back()->withSuccess('We have Recieved your inquires, We\'ll contact you soon!');
    }


    public function show($id)
    {

        $lead = Lead::where('id', $id)->first();

        return response()->json($lead);
    }

    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'zip' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['zip'] = $request->zip;
        $data['phone'] = $request->phone;
        $data['source'] = $request->source;
        $data['budget'] = $request->budget;
        $data['type'] = $request->type;


        sendNotification(Auth::id(), 'info', 'Leads Notification', 'You updated this lead #lead' . $lead->first_name . ' ' . $lead->last_name);
        User::role('admin')->chunk(100, function ($admins) use ($lead) {
            foreach ($admins as $admin) {
                sendNotification($admin->id, 'info', 'Leads Notification', 'The user : ' . Auth::user()->name . ' has updated this lead: ' . $lead->first_name . ' ' . $lead->last_name);
            }
        });


        Lead::where('id', $request->id)->update($data);

        return back()->withSuccess('Lead updated successfully!');
    }

    public function destroy(Request $request, $id)
    {


        sendNotification(Auth::id(), 'info', 'Leads Notification', 'You deleted this lead #lead_' . $id);
        User::role('admin')->chunk(100, function ($admins) use ($lead) {
            foreach ($admins as $admin) {
                sendNotification($admin->id, 'info', 'Leads Notification', 'The user : ' . Auth::user()->name . ' has deleted this lead: #lead_' . $lead->id);
            }
        });
        Lead::where('id', $id)->delete();
        return response()->json(['message' => 'Lead deleted successfully']);
    }

    public function getLeadsData(Request $request)
    {
        $user = Auth::user();

        $leads = Lead::select(['id', 'first_name', 'last_name', 'email', 'phone', 'source', 'budget', 'type', 'status', 'created_at'])
            ->where('status', '!=', 'accepted');

        if (!$user->hasRole('admin')) {
            $leads->where('user_id', Auth::id());
        }

        return DataTables::of($leads)
            ->addIndexColumn()
            ->make(true);
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->status = $request->input('status');
        $lead->save();

        if ($lead->status == 'accepted') {
            $deal = new Deal();
            $deal->lead_id = $lead->id;
            $deal->responsible_id = Auth::user()->id;
            $deal->status = 'accepted';
            $deal->save();
        }

        return response()->json(['success' => true]);
    }


}
