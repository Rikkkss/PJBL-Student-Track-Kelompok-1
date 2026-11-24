<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
