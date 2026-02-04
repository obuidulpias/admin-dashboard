<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'email_type_id',
        'to_email',
        'subject',
        'body',
        'status',
        'attempts',
        'max_attempts',
        'error',
        'sent_at',
        'last_attempt_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'last_attempt_at' => 'datetime',
        ];
    }

    /**
     * Get the email type for this log.
     */
    public function emailType(): BelongsTo
    {
        return $this->belongsTo(EmailType::class);
    }

    /**
     * Check if the email can be retried.
     */
    public function canRetry(): bool
    {
        return in_array($this->status, ['pending', 'failed']) 
            && $this->attempts < $this->max_attempts;
    }
}
