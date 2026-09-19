<?php

namespace App\Models;

use App\Models\Availability;
use App\Models\Category;
use App\Models\Order;
use App\Models\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Services()
    {
        // Explicit foreign key: the method name would otherwise make Eloquent
        // look for `services_id`, which this table has never had.
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }
}
