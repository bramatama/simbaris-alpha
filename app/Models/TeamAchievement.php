<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamAchievement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_achievements';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'achievement_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['participation_id', 'event_champion_id', 'certificate_path'];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }

    public function eventChampion(): BelongsTo
    {
        return $this->belongsTo(EventChampion::class, 'event_champion_id');
    }
}