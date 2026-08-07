<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Champion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'champions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'champion_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['champion_category_id', 'champion_name', 'rank_position'];

    public function championCategory(): BelongsTo
    {
        return $this->belongsTo(ChampionCategory::class, 'champion_category_id');
    }

    public function eventChampions(): HasMany
    {
        return $this->hasMany(EventChampion::class, 'champion_id');
    }
}