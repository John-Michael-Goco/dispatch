<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    // Enable factory and soft delete functionality
    use HasFactory, SoftDeletes;

    // Fields that can be mass assigned
    protected $fillable = [
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'status',
        'reported_by',
        'service_id',
        'branch_id',
        'resolved_at'
    ];

    // Cast resolved_at to datetime
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Get the user who reported this incident
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // Get the service this incident belongs to
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Get the branch this incident belongs to
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
} 