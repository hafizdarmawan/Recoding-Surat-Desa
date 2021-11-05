<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLog;
use App\Admin;
use App\Helpers\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        return view('admin.account.index', compact('admin'));
    }


    public function update(AccountRequest $request)
    {
        $user = auth('admin')->user();
        $user->update($request->validated());

        Activity::add(['page' => 'Data Akun', 'description' => 'Memperbarui Data Akun']);

        return back()->with([
            'status'    => 'success',
            'message'   => 'Akun Berhasil Diperbarui!'
        ]);
    }

    public function password()
    {
        return view('admin.account.password');
    }

    public function patchPassword(PasswordRequest $request)
    {
        $data = Admin::findOrFail(auth('admin')->user()->id);
        $data->password = Hash::make($request->new_password);
        $data->save();

        Activity::add(['page'   => 'Ganti Password', 'description'  => 'Password Berhasil Diubah!']);
    }

    public function logs()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')
            ->where('user_type', 'admin')
            ->where('user_id', Auth::user()->id)
            ->get();
        return view('admin.account.logs', compact('logs'));
    }
}
