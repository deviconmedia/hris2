<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisCuti extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_cuti';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_cuti',
        'deskripsi',
        'jml_hari',
    ];

    public function pengajuanCuti(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function normaCuti(): HasMany
    {
        return $this->hasMany(NormaCuti::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($jenisCuti) {
            NormaCuti::where('jenis_cuti_id', $jenisCuti->id)->delete();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_cuti', 'deskripsi'])
            ->useLogName('jenis_cuti')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Jenis cuti has been {$eventName}");
    }

}
