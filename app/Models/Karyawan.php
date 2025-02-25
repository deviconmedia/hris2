<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'karyawan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode',
        'nama',
        'nik',
        'email',
        'telepon',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'npwp',
        'alamat',
        'tgl_gabung',
        'divisi_id',
        'jabatan_id',
        'status',
        'image_uri',
    ];



    /**
     * Get the divisi that owns the Karyawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function divisi() : BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function jabatan() : BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function normaCuti(): BelongsToMany
    {
        return $this->belongsToMany(NormaCuti::class, 'norma_cuti', 'karyawan_id', 'jenis_cuti_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'email'])
            ->useLogName('karyawan')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "karyawan has been {$eventName}");
    }

}
