<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanCuti extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_cuti';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'karyawan_id',
        'jenis_cuti_id',
        'tgl_pengajuan',
        'tgl_mulai',
        'tgl_selesai',
        'status',
        'catatan',
        'lampiran',
        'send_to',
        'approver',
        'approved_at',
        'approved_by'
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'approver', 'id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'approved_by', 'id');
    }

    public function jenisCuti(): BelongsTo
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['karyawan_id', 'jenis_cuti_id', 'tgl_pengajuan', 'catatan', 'status'])
            ->useLogName('pengajuan_cuti')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Pengajuan cuti has been {$eventName}");
    }

}
