<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_id',
        'address',
        'contact_number',
        'latitude',
        'longitude',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
