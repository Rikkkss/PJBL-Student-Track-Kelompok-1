<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MataKuliah;

class Tugas extends Model
{
    protected $fillable = [
        'mata_kuliah_id',
        'judul',
        'deskripsi',
        'deadline',
        'prioritas',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}