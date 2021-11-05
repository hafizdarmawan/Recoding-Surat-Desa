<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Letter;
use App\Submission;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['submissions']  = Submission::count();
        $data['submissionsApproved'] = Submission::where('approval_status', 1)->count();
        $data['users']      = User::count();
        $data['letters']    = Letter::count();

        return redirect('admin.home', $data);
    }
}
