<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jabatan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nama_jabatan', 'deskripsi', 'status'];

    public function karyawan() : HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
