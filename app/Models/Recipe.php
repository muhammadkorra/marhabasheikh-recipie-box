<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description"];

    public function boxes(): BelongsToMany {
        return $this->belongsToMany(Box::class);
    }

    public function ingredients(): BelongsToMany {
        return $this->belongsToMany(Ingredient::class)->withTimestamps()->withPivot('amount');
    }
}
