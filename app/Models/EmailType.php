<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailType extends Model
{
    protected $fillable = [
        'name',
        'constant',
    ];

    /**
     * Get the email templates for this email type.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class);
    }

    /**
     * Get the email logs for this email type.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }
}
