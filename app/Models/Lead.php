<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profession',
        'lead_status',
        'package_id',
    ];

    /**
     * Relationship with Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
