<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCommittee extends Model
{
    use HasFactory;

    protected $table = 'event_commitees';
    protected $primaryKey = 'event_commitee_id';

    protected $fillable = [
        'event_id',
        'commitee_id',
        'positon',
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
        return $this->belongsTo(Committee::class, 'commitee_id', 'commitee_id');
    }
}
