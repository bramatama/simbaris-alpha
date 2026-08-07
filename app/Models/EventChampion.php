<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventChampion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_champions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'event_champion_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['event_level_id', 'champion_id', 'prize_money', 'certificate', 'prize_descriptions'];

    public function eventLevel(): BelongsTo
    {
        return $this->belongsTo(EventLevel::class, 'event_level_id');
    }

    public function champion(): BelongsTo
    {
        return $this->belongsTo(Champion::class, 'champion_id');
    }

    public function teamAchievements(): HasMany
    {
        return $this->hasMany(TeamAchievement::class, 'event_champion_id');
    }
}