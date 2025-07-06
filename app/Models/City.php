<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [ 'name', 'state', 'uf', 'lat', 'lng', 'uf_code', 'ibge_code' ];

    public function trabelOrders(): HasMany
    {
        return $this->hasMany(TravelOrder::class);
    }
    
    public function stateRelation(): BelongsTo
    {
        return $this->belongsTo(State::class, 'uf_code', 'ibge_code');
    }
}
