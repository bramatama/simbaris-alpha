<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_trails';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'audit_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['participation_id', 'judge_id', 'criteria_id', 'rubric_score_id', 'score_value', 'ip_address', 'user_agent'];

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
}