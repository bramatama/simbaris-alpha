<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubricScore extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rubric_scores';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rubric_score_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['rubric_category_id', 'score_value', 'display_order'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score_value' => 'decimal:2',
    ];

    public function rubricCategory(): BelongsTo
    {
        return $this->belongsTo(RubricCategory::class, 'rubric_category_id');
    }
}