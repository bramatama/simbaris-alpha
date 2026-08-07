<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamScore extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_scores';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'score_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['participation_id', 'scoring_category_id', 'judge_id', 'total_score'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_score' => 'decimal:2',
    ];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }

    public function scoringCategory(): BelongsTo
    {
        return $this->belongsTo(ScoringCategory::class, 'scoring_category_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(Judge::class, 'judge_id');
    }
}