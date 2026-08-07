<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamScoreDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_score_details';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'score_detail_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['participation_id', 'judge_id', 'criteria_id', 'rubric_score_id', 'score_value'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score_value' => 'decimal:2',
    ];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(Judge::class, 'judge_id');
    }

    public function assessmentCriterion(): BelongsTo
    {
        return $this->belongsTo(AssessmentCriterion::class, 'criteria_id');
    }

    public function rubricScore(): BelongsTo
    {
        return $this->belongsTo(RubricScore::class, 'rubric_score_id');
    }
}