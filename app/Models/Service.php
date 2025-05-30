<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    // Enable factory and soft delete functionality
    use HasFactory, SoftDeletes;

    // Fields that can be mass assigned
    protected $fillable = [
        'name',
        'description',
        'contact_line',
    ];

    // Get all branches associated with this service
    public function branches()
    {
        return $this->hasMany(\App\Models\Branch::class);
    }
}
