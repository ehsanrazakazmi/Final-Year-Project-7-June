<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A technician availability window (e.g. "subah", 05:39 - 09:38).
 *
 * Formerly App\Models\Color - the CRUD was copied from a colours tutorial and
 * repurposed without renaming anything.
 */
class Availability extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Services()
    {
        return $this->belongsToMany(Services::class, 'availability_services', 'availability_id', 'services_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
