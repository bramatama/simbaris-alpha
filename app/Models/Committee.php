<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $committee_id
 * @property int $user_id
 * @property string $department
 */
class Committee extends Model
{
    use HasFactory;

    protected $table = 'committees';
    protected $primaryKey = 'committee_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department',
    ];

    /**
     * Get the user that owns the committee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the event committee assignments for this committee.
     */
    public function eventCommittees(): HasMany
    {
        return $this->hasMany(EventCommittee::class, 'committee_id', 'committee_id');
    }
}
