<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LetterRequest;
use App\Letter;
use App\Submission;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index()
    {
        $letters = Letter::all();
        return view('admin.letter.index', compact('letters'));
    }

    public function create()
    {
        return view('admin.letter.create');
    }

    public function store(LetterRequest $request)
    {
        $letter = new Letter();
        $letter->name       = $request->name;
        $letter->content    = $request->content;
        $letter->data       = json_encode($request->data);
        $letter->status     = $request->status;
        $letter->save();

        Activity::add([
            'page'          => 'Daftar Surat',
            'description'   => 'Menambahkan Surat Baru:' . $request->name
        ]);
        return redirect()->route('admin.letters.index')->with([
            'status'    => 'success',
            'message'   => 'Menambahkan Surat Baru:' . $request->name
        ]);
    }

    public function show(Letter $letter)
    {
        $submissions = Submission::with('user', 'admin')->where([
            'letter_id'         => $letter->id,
            'approval_status'   => 1,
        ])->get();

        return view('admin.letter.show', compact('letter', 'submissions'));
    }

    public function edit(Letter $letter)
    {
        return view('admin.letter.edit', compact('letter'));
    }

    public function update(LetterRequest $request, Letter $letter)
    {
        $letter->name       = $request->name;
        $letter->content    = $request->content;
        $letter->data       = json_encode($request->data);
        $letter->status     = $request->status;
        $letter->save();

        Activity::add([
            'page'          => 'Edit Surat',
            'description'   => 'Memperbarui Surat:' . $letter->name
        ]);

        return redirect()->route('admin.letters.index')->with([
            'status'    => 'success',
            'message'   => 'Berhasil Memperbarui Surat:' . $letter->name
        ]);
    }

    public function destroy(Letter $letter)
    {
        $letter->delete();
        Activity::add([
            'page'          => 'Daftar Surat',
            'description'   => 'Menghapus Surat: ' . $letter->name
        ]);

        return back()->with([
            'status'    => 'success',
            'message'   => 'Surat Berhasil Dihapus'
        ]);
    }
}
