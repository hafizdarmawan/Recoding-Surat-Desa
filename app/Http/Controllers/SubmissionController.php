<?php

namespace App\Http\Controllers;

use App\Letter;
use App\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::where('user_id', auth()->id());
        return view('submissions', compact('submissions'));
    }


    public function store($letter, Request $request)
    {
        $letter = Letter::find($letter);
        if (!$letter) {
            return back()->with([
                'status'    => 'danger',
                'message'   => 'Surat Belum Tersedia'
            ]);
        }
        $submissions = new Submission();
        $submissions->user_id       = auth()->id();
        $submissions->letter_id     = $letter->id;
        $submissions->data          = json_encode($request->except('_token'));
        $submissions->approval_status = 0;
        $submissions->save();

        $response = Http::withHeaders(['Authorization' => config('whatsapp.token')])
            ->asForm()
            ->post('https://fonnte.com/api/send_message.php', [
                'phone' => setting('whatsapp'),
                'type'  => 'text',
                'text'  => 'Ada Pengajuan Surat Baru Oleh:' . auth()->user()->name . ' Dan Surat Yang Diajukan Adalah :' . $letter->name . '. Mohon Segera DiTinjau, Terimakasih. E-Surat Pemerintah Desa Tremas'
            ]);

        Activity::add(['page' => 'Pengajuan Surat', 'description' => 'Mengajukan Surat Baru:' . $letter->name]);

        return back()->with([
            'status'    => 'success',
            'message'   => 'Berhasil Mengajukan Surat! Mohon Tunggu Notifikasi Via Whatsapp'
        ]);
    }
}
