<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use HasFactory;

    protected $table = 'lead_statuses';

    protected $fillable = [
        
        'name',
        'color',
        'order_by',   // ✅ add this
        'is_active',
        'is_global',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_global' => 'boolean',
        'order_by'  => 'integer',
    ];

    /**
     * Scope to fetch statuses that are global or belong to a specific company
     */
    
    /**
     * Scope for default ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_by')->orderBy('id');
    }
}
