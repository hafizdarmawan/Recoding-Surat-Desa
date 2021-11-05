<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Activity;
use App\Http\Controllers\Controller;
use App\Imports\AdminsImport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function admin(Request $request)
    {
        Excel::import(new AdminsImport, request()->file('file_import'));
        Activity::add([
            'page'          => 'Admin',
            'description'   => 'Mengimport Data Admin'
        ]);

        return redirect()->route('admin.data.index')->with([
            'status'    => 'success',
            'message'   => 'Mengimport Data Admin'
        ]);
    }


    public function user(Request $request)
    {
        Excel::import(new UsersImport, request()->file('file_import'));
        Activity::add(['page'   => 'Warga', 'description' => 'Mengimport Data Warga']);
        return redirect()->route('admin.users.index')->with([
            'status'    => 'success',
            'message'   => 'Mengimport Data Warga'
        ]);
    }
}
