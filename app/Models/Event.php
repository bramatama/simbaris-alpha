<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'public_id',
        'event_name',
        'description',
        'location',
        'status',
        'registration_start_time',
        'registration_end_time',
        'start_time',
        'end_time',
        'created_by',
    ];

    protected $casts = [
        'registration_start_time' => 'datetime',
        'registration_end_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin that created the event.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by', 'user_id');
    }

    /**
     * Get the participations for this event.
     */
    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class, 'event_id', 'event_id');
    }

    /**
     * Get the event judges for this event.
     */
    public function eventJudges(): HasMany
    {
        return $this->hasMany(EventJudge::class, 'event_id', 'event_id');
    }

    /**
     * Get the event committees for this event.
     */
    public function eventCommittees(): HasMany
    {
        return $this->hasMany(EventCommittee::class, 'event_id', 'event_id');
    }
}
