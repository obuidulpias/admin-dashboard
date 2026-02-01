<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    protected $fillable = [
        'email_type_id',
        'subject',
        'body',
        'variables',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
        ];
    }

    /**
     * Get the email type that owns this template.
     */
    public function emailType(): BelongsTo
    {
        return $this->belongsTo(EmailType::class);
    }
}
