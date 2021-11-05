<?php

namespace App\Imports;

use App\Admin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class AdminsImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new Admin([
            'name'      => ucwords($row['name']),
            'username'  => strtolower($row['username']),
            'password'  => Hash::make($row['password']),
        ]);
    }
}
