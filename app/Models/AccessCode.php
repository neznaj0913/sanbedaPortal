<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'is_used', 'expires_at'];

    public function isValid()
    {
        return !$this->is_used && (!$this->expires_at || Carbon::now()->lt($this->expires_at));
    }
}
