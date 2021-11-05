<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Signatory;
use App\Http\Requests\Admin\SignatoryRequest;
use App\Helpers\Activity;


class SignatoryController extends Controller
{
    public function index()
    {
        $signatories = Signatory::all();
        return view('admin.signatory.index', compact('signatories'));
    }

    public function create()
    {
        return view('admin.signatory.create');
    }

    public function store(SignatoryRequest $request)
    {
        Signatory::create($request->validated());
        Activity::add([
            'page'          => 'Penandatangan',
            'description'   => 'Meambah Penandatangan Baru'
        ]);

        return redirect()->route('admin.signatory.index')->with([
            'status'    => 'success',
            'message'   => 'Menambah Penandatangan Baru:' . $request->name
        ]);
    }

    public function show(Signatory $signatory)
    {
        return view('admin.ignatory.show', compact('signatory'));
    }

    public function edit(Signatory $signatory)
    {
        return view('admin.signatory.edit', compact('signatory'));
    }

    public function update(SignatoryRequest $request, Signatory $signatory)
    {
        $signatory->update($request->validated());
        Activity::add([
            'page'          => 'Edit Penandatangan',
            'description'   => 'Memperbarui Penanatangan:' . $signatory->name
        ]);

        return redirect()->route('admin.signatories.index')->with([
            'status'    => 'success',
            'message'   => 'Berhasil Memperbarui Penandatangan:' . $signatory->name
        ]);
    }

    public function destroy(Signatory $signatory)
    {
        Activity::add([
            'page'          => 'Penandatangan',
            'description'   => 'Mengahapus Penandatangan:' . $signatory->name
        ]);
        $signatory->delete();
        return back()->with([
            'status'    => 'sucess',
            'message'   => 'Penndatangan Berhasil Dihapus'
        ]);
    }

}
