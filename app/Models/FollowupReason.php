<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowupReason extends Model
{
    use HasFactory;

    /* ================= FILLABLE ================= */

    protected $fillable = [
        
        'name',
        'remark',
        'date',
        'time',
        'email_template',
        'whatsapp_template',
        'is_active',
        'is_global',
    ];

    /* ================= CASTS ================= */

    protected $casts = [
        'remark'    => 'boolean',
        'date'      => 'boolean',
        'time'      => 'boolean',
        'is_active' => 'boolean',
        'is_global' => 'boolean',
    ];

    /* ================= RELATION ================= */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /* ================= SCOPES ================= */

    /**
     * Only active reasons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Company reasons with global fallback
     * - If company has reasons → only company
     * - Else → global
     */
    

    /**
     * Only global reasons
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /* ================= MUTATORS ================= */

    /**
     * If is_global = true → company_id must be NULL
     */
    

    /**
     * If company_id is set → is_global must be false
     */
   
}
