<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    public function supplier(): BelongsTo {
        return $this->belongsTo(Supplier::class);
    }

    public function recipes(): BelongsToMany {
        return $this->belongsToMany(Recipe::class);
    }
}
