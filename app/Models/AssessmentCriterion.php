<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentCriterion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assessment_criteria';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'criteria_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['scoring_category_id', 'criteria_name', 'order_index'];

    public function scoringCategory(): BelongsTo
    {
        return $this->belongsTo(ScoringCategory::class, 'scoring_category_id');
    }

    public function rubricCategories(): HasMany
    {
        return $this->hasMany(RubricCategory::class, 'criteria_id');
    }
}