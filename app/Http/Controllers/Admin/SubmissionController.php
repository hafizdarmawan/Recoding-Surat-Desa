<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Submission;
use App\Signatory;
use App\Exports\SubmissionExport;
use App\Helpers\Activity;
use App\Helpers\Fonnte;
use Maatwebsite\Excel\Excel;

class SubmissionController extends Controller
{
    public function pending()
    {
        $submissions = Submission::with('user', 'letter')
            ->where('approval_status', 0)
            ->get();
        return view('admin.submission.pending', compact('submissions'));
    }

    public function approved()
    {
        $submissions = Submission::with('user', 'letter')
            ->where('approval_status', 1)
            ->get();
        return view('admin.submission.approved', compact('submissions'));
    }

    public function exportApproved()
    {
        return Excel::download(new SubmissionExport, 'Surat Yang Disetujui' . now()->format('d-M-Y') . '.xlsx');
    }

    public function rejected()
    {
        $submissions = Submission::with('user', 'letter', 'admin')
            ->where('approval_status', 2)
            ->get();
        return view('admin.submission.rejected', compact('submissions'));
    }

    public function show($id)
    {
        $submission     = Submission::find($id);
        $signatories    = Signatory::all();
        return view('admin.submission.show', compact('submission', 'signatories'));
    }

    public function status($id, $status)
    {
        $submission = Submission::find($id);
        $submission->approval_status = $status;
        $submission->approval_at = now();
        $submission->admin_id = auth('admin')->user()->id;
        $submission->save();

        if ($status == 1) {
            $message = 'Pengajuan' . $submission->letter->name .
                'Oleh: ' . $submission->user->name . 'telah disetujui.
                    Silahkan datang ke Kantor Desa dengan Menggunakan Masker!';
        } else {
            $message = 'Pengajuan ' . $submission->letter->name . 'Oleh:' . $submission->user->name .
                'telah ditolak';
        }

        // dd($submission->user->phone_number);

        // helper Menggunakan fonte
        Fonnte::kirim([
            'phone' => $submission->user->phone_number,
            'text'  => $message
        ]);
        
        Activity::add([
            'page'          => 'Warga',
            'description'   => 'Berhasil Mengubah Status Pengajuan Surat: #' . $id
        ]);

        $route = $status == 1 ? route('admin.submissions.approved') : route('admin.submissions.rejected');
        return redirect($route)->with([
            'status'    => 'success',
            'message'   => 'Mengubah Status Pengajuan Surat: #' . $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $submission = Submission::find($id);
        $submission->number = $request->number;
        $submission->data   = json_encode($request->except('_token', '_method', 'number'));
        $submission->save();
        Activity::add([
            'page'          => 'Warga',
            'description'   => 'Berhasil Memperbarui Pengajuan Surat: #' . $id
        ]);
        return back()->with([
            'status' => 'success',
            'message' => 'Memperbarui Pengajuan Surat: #' . $id
        ]);
    }

    public function print($id, Request $request)
    {
        $submission = Submission::find($id);
        $signatory_id  = $request->signatory ?? setting('signatory_active');
        $signatory  = Signatory::find($signatory_id);
        $data['signatory_name'] = $signatory->name;
        $data['signatory_position'] = $signatory->position;
        $data['on_behalf'] = $signatory_id != 1 ? 'A/N, Perbengkelan' . ucwords(strtolower(setting('village'))) : '';


        foreach ($submission->user->toArray() as $key => $value) {
            $data[$key] = $value;
        }

        $data['ttl']    = $submission->user->getPsb();
        $data['tgl']    = now()->formatLocalized('%d %B %Y');
        $data['districts'] = ucwords(strtolower(setting('districts')));
        $data['sub-districts'] = ucwords(strtolower(setting('sub-districts')));
        $data['village'] = ucwords(strtolower(setting('village')));

        foreach (json_decode($submission->data) as $key => $value) {
            $data[$key] = $value;
        }

        $content = $submission->letter->content;
        foreach ($data as $key => $value) {
            $content = str_replace('[' . $key . ']', $value, $content);
        }
        return view('admin.print.template', compact('submission', 'content'));
    }



}
