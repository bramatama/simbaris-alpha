<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $judge_id
 * @property int $user_id
 */
class Judge extends Model
{
    use HasFactory;

    protected $table = 'judges';
    protected $primaryKey = 'judge_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the judge.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the event judge assignments for this judge.
     */
    public function eventJudges(): HasMany
    {
        return $this->hasMany(EventJudge::class, 'judge_id', 'judge_id');
    }
}
