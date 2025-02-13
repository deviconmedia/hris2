<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsToMany(NormaCuti::class);
    }

}
