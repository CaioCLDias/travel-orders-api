<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'uf', 'ibge_code'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'uf_code', 'ibge_code');
    }
}
