<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubricCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rubric_categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rubric_category_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['criteria_id', 'category_label', 'category_order'];

    public function assessmentCriterion(): BelongsTo
    {
        return $this->belongsTo(AssessmentCriterion::class, 'criteria_id');
    }

    public function rubricScores(): HasMany
    {
        return $this->hasMany(RubricScore::class, 'rubric_category_id');
    }
}