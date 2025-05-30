<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    // Enable factory functionality
    use HasFactory;

    // Specify the table name
    protected $table = 'user_info';

    // Fields that can be mass assigned
    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'email',
        'address',
        'date_of_birth',
        'description'
    ];

    // Cast date_of_birth to date type
    protected $casts = [
        'date_of_birth' => 'date'
    ];

    // Get the user this info belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 