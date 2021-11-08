<?php

use App\Signatory;
use Illuminate\Database\Seeder;

class SignatorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Signatory::create(['name' => 'Joko Widodo', 'position' => 'Kepala Desa']);
    }
}
