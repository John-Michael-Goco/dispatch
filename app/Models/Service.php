<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'contact_line',
    ];

    public function branches()
    {
        return $this->hasMany(\App\Models\Branch::class);
    }
}
