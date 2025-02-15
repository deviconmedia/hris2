<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamKehadiran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rekam_kehadiran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tgl_rekam',
        'jam_masuk',
        'jam_pulang',
        'lokasi',
        'status',
    ];

    public function karyawan() : BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function shift() : BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

}
