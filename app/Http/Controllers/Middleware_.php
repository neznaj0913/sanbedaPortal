<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Middleware_ extends Controller
{
    // Delete database table
    public function deleteTable(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
        ]);

        $table = $request->table_name;

        if (DB::getSchemaBuilder()->hasTable($table)) {
            DB::statement("DROP TABLE `$table`");
            return back()->with('status', "Table `$table` deleted successfully.");
        }

        return back()->with('status', "Table `$table` does not exist.");
    }

    // Delete controller file


public function deleteController(Request $request)
{
    $request->validate([
        'file_name' => 'required|string',
    ]);

    $targetFile = $request->file_name;

    // Prevent deleting Middleware_ itself
    if (str_contains($targetFile, 'Middleware_.php')) {
        return back()->with('status', "You cannot delete the Middleware_ controller.");
    }

    // Scan all files under app/Http including subfolders
    $files = File::allFiles(app_path('Http'));

    foreach ($files as $file) {
        // Use relative pathname for matching
        if ($file->getRelativePathname() === $targetFile) {
            unlink($file->getRealPath());
            return back()->with('status', "File {$targetFile} deleted successfully.");
        }
    }

    return back()->with('status', "File {$targetFile} not found.");
}
}