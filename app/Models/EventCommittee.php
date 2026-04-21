<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCommittee extends Model
{
    use HasFactory;

    protected $table = 'event_committees';
    protected $primaryKey = 'event_committee_id';

    protected $fillable = [
        'event_id',
        'committee_id',
        'position',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the event that this assignment belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    /**
     * Get the committee that this assignment belongs to.
     */
    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class, 'committee_id', 'committee_id');
    }
}
