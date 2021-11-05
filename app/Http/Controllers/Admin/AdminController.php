<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLog;
use App\Admin;
use App\Helpers\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.data.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.data.create');
    }

    public function store(AdminRequest $request)
    {
        $data = new Admin();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = 'uploads/' . auth()->user()->id . '/';

            if (!file_exists(public_path($path))) {
                mkdir($path, 666, true);
            }

            $path .= time() . '.' . $file->getClientOriginalExtension();
            $image = Image::make($file)->resize(300, 300);
            $image->save(public_path($path));
            $data->photo = $path;
        }
        $data->name     = $request->name;
        $data->username = $request->username;
        $data->password = Hash::make($request->password);
        $data->save();
    }

    public function show(Admin $data)
    {
        $admin = $data;
        $logs = ActivityLog::where([
            'user_type' => 'admin',
            'user_id'   => $data->id
        ])->get();
        return view('admin.data.show', compact('admin', 'logs'));
    }

    public function edit(Admin $data)
    {
        $admin = $data;
        return view('admin.data.edit', compact('admin'));
    }


    public function update(AdminRequest $request, Admin $data)
    {
        if ($request->hasFile('photo')) {
            $file   = $request->file('photo');
            $path   = 'uploads/' . auth()->user()->id . '/' . time() . '.' . $file->getClientOriginalExtension();
            $image  = Image::make($file)->resize(300, 300);
            $image->save(public_path($path));
            $data->photo = $path;
        }

        $data->name = $request->name;
        $data->username = $request->username;

        if ($request->password) {
            $data->password = Hash::make($request->password);
        }
        $data->save();
        Activity::add([
            'page'   => 'Admin',
            'description'   => 'Memperbarui Data Admin'
        ]);

        return redirect()->route('admin.data.index')->with([
            'status'    => 'success',
            'message'   => 'Berhasil Memperbarui Data Admin' . $request->name
        ]);
    }


    public function destroy(Admin $data)
    {
        Activity::add([
            'page'          => 'Admin',
            'description'   => 'Menghapus Data Admin: ' . $data->name
        ]);
        return back()->with([
            'status'    => 'success',
            'message'   => 'Data Berhasil Dihapus'
        ]);
    }
}
