<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

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
        'email',            
        'created_at',
        'updated_at',
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
