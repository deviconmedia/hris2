<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekamKehadiran extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['karyawan_id', 'shift_id', 'jam_masuk', 'jam_pulang', 'status'])
            ->useLogName('rekam_kehadiran')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Presensi has been {$eventName}");
    }

}
