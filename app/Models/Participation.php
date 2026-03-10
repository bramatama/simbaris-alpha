<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasFactory;

    protected $table = 'participations';
    protected $primaryKey = 'participation_id';

    protected $fillable = [
        'event_id',
        'official_team_id',
        'team_name',
        'status',
        'payment_proof_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the event that this participation belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    /**
     * Get the official team that this participation belongs to.
     */
    public function officialTeam(): BelongsTo
    {
        return $this->belongsTo(OfficialTeam::class, 'official_team_id', 'official_team_id');
    }
}
