<?php
// ContactRequestController.php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class ContactController extends Controller
{
    public function index()
    {
        return view('backend.contacts.contact'); // This points to the Blade view we created
    }

    public function store(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'interest' => 'required|string|max:255',
            'message' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }


        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->countryCode . '' .$request->phone;
        $data['country'] = $request->country;
        $data['message'] = $request->message;
        $data['interest'] = $request->interest;

        Contact::create($data);



        return back()->withSuccess('we have received your message and we will get back to you as soon as possible!');
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

    public function apiIndex()
    {
        // Start building the query
        $query = Contact::latest();

        // Fetch the filtered contact requests
        $contactRequests = $query->get();

        // Return data for DataTables
        return DataTables::of($contactRequests)
            ->addIndexColumn()
            ->addColumn('code', function ($row) {
                return $row->type ? $row->type->name : 'N/A'; // Display type name or 'N/A'
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->diffForHumans() : 'N/A'; // Display time difference
            })
            ->make(true);
    }

}
