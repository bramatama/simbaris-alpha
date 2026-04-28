<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $event_judge_id
 * @property int $event_id
 * @property int $judge_id
 * @property string|null $expertise
 * @property string|null $secondary_expertise
 */
class EventJudge extends Model
{
    use HasFactory;

    protected $table = 'event_judges';
    protected $primaryKey = 'event_judge_id';

    protected $fillable = [
        'event_id',
        'judge_id',
        'expertise',
        'secondary_expertise',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the event that this assignment belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    /**
     * Get the judge that this assignment belongs to.
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(Judge::class, 'judge_id', 'judge_id');
    }
}
