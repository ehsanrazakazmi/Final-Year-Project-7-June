<?php

namespace App\Models;

use App\Models\Availability;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Services extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    //has one category
    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    // has one or many availability windows
    public function availabilities()
    {
        // belongsToMany(related, pivotTable, foreignPivotKey, relatedPivotKey)
        // foreignPivotKey is THIS model's key (services_id), relatedPivotKey is
        // the Availability key. Getting these the wrong way round silently
        // writes swapped pivot rows.
        return $this->belongsToMany(Availability::class, 'availability_services', 'services_id', 'availability_id');
    }


}


