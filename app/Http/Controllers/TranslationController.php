<?php
// app/Http/Controllers/TranslationController.php
namespace App\Http\Controllers;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller {
    public function index(Language $language) {
        $translations = $language->translations()->orderBy('key')->get();
        return view('backend.translations.index', compact('language','translations'));
    }

    public function store(Request $request, Language $language) {
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);

        Translation::updateOrCreate(
            ['key'=>$data['key'],'language_id'=>$language->id],
            ['value'=>$data['value']]
        );

        return back()->with('success','Translation saved.');
    }

    public function sync(Language $language) {
        $items = $language->translations()->pluck('value','key')->toArray();
        $path = resource_path("lang/{$language->code}");
        if(!File::exists($path)){
            File::makeDirectory($path,0777,true);
        }
        File::put("$path/app.php","<?php\n\nreturn ".var_export($items,true).";\n");

        return back()->with('success','Language file synced.');
    }
}
