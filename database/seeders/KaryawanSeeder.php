<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'kode' => '18022025001',
            'nama' => 'Efron Paduansi',
            'nik' => '5319020308990001',
            'email' => 'eufrondpaduansi@iconmedia.co.id',
            'telepon' => '081359856450',
            'tempat_lahir' => 'Lento',
            'divisi_id' => 1,
            'jabatan_id' => 1,
            'tgl_lahir' => '1999-08-03',
            'jenis_kelamin' => 'L',
            'alamat' => 'Pamulang',
            'tgl_gabung' => '2022-10-20',
        ]);
    }
}
