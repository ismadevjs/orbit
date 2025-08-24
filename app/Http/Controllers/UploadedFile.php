<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class UploadedFile extends Controller
{
    public function index(Request $request, $directory = null)
    {
        // If a directory is provided, list files in that directory
        $path = $directory ? 'public/' . $directory : 'public';

        // Get files and directories
        $files = Storage::disk('public')->files($directory);
        $directories = Storage::disk('public')->directories($directory);

        // Search functionality
        $search = $request->input('search');
        if ($search) {
            $files = array_filter($files, function ($file) use ($search) {
                return str_contains(basename($file), $search);
            });
        }

        // Pagination
        $currentPage = $request->input('page', 1);
        $perPage = 10;
        $currentItems = array_slice($files, ($currentPage - 1) * $perPage, $perPage);
        $files = new LengthAwarePaginator($currentItems, count($files), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('backend.uploaded_files.index', [
            'files' => $files,
            'directories' => $directories,
            'search' => $search,
            'currentDirectory' => $directory,
        ]);
    }

    public function destroy(Request $request, $directory, $file)
    {
        $path = $directory ? $directory . '/' . $file : $file;

        // Check if the file exists before trying to delete it
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            \Log::info("File deleted successfully: " . $path);
        } else {
            \Log::warning("File not found: " . $path);
        }

        return redirect()->route('uploaded_files.index', $directory)->with('success', 'File deleted successfully.');
    }


}
