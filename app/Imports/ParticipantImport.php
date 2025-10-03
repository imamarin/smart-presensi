<?php

namespace App\Imports;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;

class ParticipantImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Participant::updateOrCreate(
                ['no_induk' => $row['no_induk']], // unique key
                [
                    'nama' => $row['nama'],
                    'id_kartu' => $row['id_kartu'],
                    'no_hp' => $row['no_hp'],
                    'alamat' => $row['alamat'],
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.no_induk' => ['required'],
            '*.nama' => ['required'],
            '*.id_kartu' => ['required'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'no_induk.required' => 'No Induk wajib diisi.',
            'id_kartu.required' => 'ID Kartu wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
        ];
    }
}
