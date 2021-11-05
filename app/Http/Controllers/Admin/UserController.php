<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserRequest;
use App\User;
use App\ActivityLog;
use Carbon\Carbon;
use App\Helpers\Activity;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(UserRequest $request)
    {
        $user = new User();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = 'uploads/' . auth()->user()->id . '/';
            if (!file_exists(public_path($path))) {
                mkdir($path, 666, true);
            }

            $path .= time() . '.' . $file->getClientOriginalExtension();
            $image = Image::make($file)->resize(300, 300);
            $image->save(public_path($path));
            $user->photo = $path;
        }

        $user->sin          = $request->sin;
        $user->name         = $request->name;
        $password           = $request->password ?? Carbon::parse($request->birth_date)->format('d-m-Y');
        $user->password     = Hash::make($password);
        $user->birth_date   = $request->birth_date;
        $user->birth_place  = $request->birth_place;
        $user->gender       = $request->gender;
        $user->address      = $request->address;
        $user->religion     = $request->religion;
        $user->marital_status = $request->marital_status;
        $user->profession   = $request->profession;
        $user->phone_number = $request->phone_number;
        $user->save();

        Activity::add([
            'page'          => 'Warga',
            'description'   => 'Menambah Data Warga' . $request->name
        ]);

        return redirect()->route('admin.users.index')->with([
            'status'    => 'success',
            'message'   => 'Menambahkan Warga Baru' . $request->name
        ]);
    }


    public function show(User $user)
    {
        $logs = ActivityLog::where([
            'user_type ' => 'user',
            'user_id'   => $user->id
        ]);
        return view('admin.user.show', compact('user', 'logs'));
    }

    public function edit(User $user)
    {
        return redirect('admin.user.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = 'uploads/' . auth()->user()->id . '/' . time() . '.' . $file->getClientOriginalExtension();
            $image = Image::make($file)->resize(300, 300);
            $image->save(public_path($path));
            $user->photo = $path;
        }

        $user->sin = $request->sin;
        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->birth_place = $request->birth_place;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->religion = $request->religion;
        $user->marital_status = $request->marital_status;
        $user->profession = $request->profession;
        $user->phone_number = $request->phone_number;
        $user->save();

        Activity::add(['page' => 'User', 'description' => 'Menmperbarui Data Warga: ' . $user->name]);

        return redirect()->route('admin.users.index')->with([
            'status' => 'success',
            'message' => 'Berhasil Memperbarui Data Warga: ' . $request->name
        ]);
    }

    public function destroy(User $user)
    {
        Activity::add([
            'page'          => 'User',
            'description'   => 'Menghapus Data Warga:' . $user->name
        ]);
        $user->delete();
        return back()->with([
            'status'    => 'success',
            'message'   => 'Data Berhasil Dihapus'
        ]);
    }



}
