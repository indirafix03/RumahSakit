<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed Poli
        DB::table('polis')->insert([
            ['nama_poli' => 'Poli Umum', 'deskripsi' => 'Pelayanan kesehatan umum', 'created_at' => now()],
            ['nama_poli' => 'Poli Gigi', 'deskripsi' => 'Pelayanan kesehatan gigi dan mulut', 'created_at' => now()],
            ['nama_poli' => 'Poli Anak', 'deskripsi' => 'Pelayanan kesehatan anak', 'created_at' => now()],
            ['nama_poli' => 'Poli Kandungan', 'deskripsi' => 'Pelayanan kesehatan ibu dan kandungan', 'created_at' => now()],
        ]);

        // Seed Admin
        DB::table('users')->insert([
            'name' => 'Admin Rumah Sakit',
            'email' => 'admin@rs.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Beberapa Dokter
        $dokters = [
            [
                'name' => 'Dr. Ahmad Wijaya',
                'email' => 'ahmad@rs.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'poli_id' => 1,
                'spesialisasi' => 'Dokter Umum',
                'bio' => 'Spesialis penyakit dalam',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Siti Rahayu',
                'email' => 'siti@rs.com', 
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'poli_id' => 2,
                'spesialisasi' => 'Dokter Gigi',
                'bio' => 'Spesialis gigi dan mulut',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($dokters as $dokter) {
            DB::table('users')->insert($dokter);
        }

        // Seed Beberapa Obat
        DB::table('medicines')->insert([
            [
                'nama_obat' => 'Paracetamol',
                'deskripsi' => 'Obat penurun demam dan pereda nyeri',
                'tipe_obat' => 'biasa',
                'stok' => 100,
                'created_at' => now(),
            ],
            [
                'nama_obat' => 'Amoxicillin',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'tipe_obat' => 'keras', 
                'stok' => 50,
                'created_at' => now(),
            ],
        ]);
    }
}