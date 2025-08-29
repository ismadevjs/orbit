<?php

// app/Http/Controllers/LanguageController.php
namespace App\Http\Controllers;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller {
    public function index() {
        $languages = Language::all();
        return view('backend.languages.index', compact('languages'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => 'required|string|max:5|unique:languages,code',
            'name' => 'required|string|max:50',
        ]);
        Language::create($data);
        return back()->with('success','Language added.');
    }

    public function destroy(Language $language) {
        $language->delete();
        return back()->with('success','Language deleted.');
    }
}
