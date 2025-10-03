<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Shift;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'imam',
            'email' => 'imam@gmail.com',
            'password' => bcrypt('12345'),
            'level' => 'admin'
        ]);
        \App\Models\User::factory(10)->create();
        // \App\Models\DetailShift::factory(6)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $groups = \App\Models\Group::factory(5)->create();

        // 2. Buat 10 peserta dan assign ke group acak
        $participants = \App\Models\Participant::factory(10)->create();

        // 3. Buat 3 jam kerja
        $jamKerjas = \App\Models\JamKerja::factory(3)->create();

        // 4. Buat 3 shift dan relasikan ke jam kerja
        $shifts = \App\Models\Shift::factory(3)->make()->each(function ($shift) use ($jamKerjas) {
            $shift->id_jam_kerja = $jamKerjas->random()->id;
            $shift->save();
        });


        // 5. Assign shift ke participant
        foreach ($participants as $participant) {
            \App\Models\JadwalParticipant::factory()->create([
                'id_participant' => $participant->id,
                'id_shift' => $shifts->random()->id,
            ]);
        }

        foreach ($participants as $participant) {
            $assignedGroups = $groups->random(rand(1, 2));

            foreach ($assignedGroups as $group) {
                \App\Models\GroupParticipant::factory()->create([
                    'id_participant' => $participant->id,
                    'id_group' => $group->id,
                ]);
            }
        }

        // 6. Buat 2 device
        $devices = \App\Models\Device::factory(2)->create();

        // 7. Buat presensi untuk tiap peserta, 1x saja per orang
        foreach ($participants as $participant) {
            \App\Models\Presensi::factory()->create([
                'id_participant' => $participant->id,
                'id_device' => $devices->random()->id,
                'id_shift' => $shifts->random()->id,
            ]);
        }

        // 8. Buat 2 hari libur dan assign ke grup acak
        $liburs = \App\Models\WaktuLibur::factory(2)->create();

        foreach ($liburs as $libur) {
            \App\Models\GroupLibur::factory()->create([
                'id_waktu_libur' => $libur->id,
                'id_group' => $groups->random()->id,
            ]);
        }
    }
}
