<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficialTeam extends Model
{
    use HasFactory;

    protected $table = 'official_teams';
    protected $primaryKey = 'official_team_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'province',
        'city',
        'level',
        'institution',
    ];

    /**
     * Get the user that owns the official team.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the participations for this official team.
     */
    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class, 'official_team_id', 'official_team_id');
    }
}
