<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChampionCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'champion_categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'champion_category_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['champion_category_name'];

    public function champions(): HasMany
    {
        return $this->hasMany(Champion::class, 'champion_category_id');
    }
}