<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_members';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'team_member_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['participation_id', 'member_name', 'member_role', 'member_position', 'member_photo_path'];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}