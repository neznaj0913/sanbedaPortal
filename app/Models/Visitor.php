<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    // 👇 Ensure all relevant columns from your DB are mass assignable
    protected $fillable = [
        'gatepass_no',
        'first_name',
        'last_name',
        'department',
        'company_affiliation',
        'contact_person',
        'contact_info',
        'purpose',
        'additional_notes',
        'time_in',
        'time_out',
        'status',
        'email',             // ✅ added because it exists in your DB
        'created_at',
        'updated_at',
    ];

    // 👇 Helpful computed attribute for convenience
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // 👇 Optional: automatically cast time fields to Carbon
    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
