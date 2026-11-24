<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // AKUN ADMIN
            $admin = User::updateOrCreate(
                ['email' => 'admin@studenttrack.test'],
                [
                    'name' => 'Administrator StudentTrack',
                    'password' => 'AdminTrack2026!',
                    'role' => 'admin',
                ]
            );

            // 4 AKUN MAHASISWA
            $mahasiswa = [
                [
                    'name' => 'Mahasiswa 1',
                    'email' => 'mahasiswa1@studenttrack.test',
                ],
                [
                    'name' => 'Mahasiswa 2',
                    'email' => 'mahasiswa2@studenttrack.test',
                ],
                [
                    'name' => 'Mahasiswa 3',
                    'email' => 'mahasiswa3@studenttrack.test',
                ],
                [
                    'name' => 'Mahasiswa 4',
                    'email' => 'mahasiswa4@studenttrack.test',
                ],
            ];

            foreach ($mahasiswa as $item) {
                User::updateOrCreate(
                    ['email' => $item['email']],
                    [
                        'name' => $item['name'],
                        'password' => 'Mahasiswa2026!',
                        'role' => 'mahasiswa',
                    ]
                );
            }

            // Pindahkan data lama yang belum memiliki pemilik
            // ke akun Admin agar tidak hilang.
            DB::table('mata_kuliah')
                ->whereNull('user_id')
                ->update(['user_id' => $admin->id]);

            DB::table('tugas')
                ->whereNull('user_id')
                ->update(['user_id' => $admin->id]);

            DB::table('nilais')
                ->whereNull('user_id')
                ->update(['user_id' => $admin->id]);
        });
    }
}
