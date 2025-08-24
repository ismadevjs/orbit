<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NewsLetterController extends Controller
{
    public function index(Request $request)
    {
        return view('backend.newsletter.newsletter');
    }


    public function apiIndex()
    {
        $newsletter = NewsLetter::query();

        return DataTables::of($newsletter)
            ->addIndexColumn()
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Newsletter::create(['email' => $request->input('email')]);

        \Log::info($request->all());
        return back()->withSuccess('Thanks for signing up!');
    }
}
