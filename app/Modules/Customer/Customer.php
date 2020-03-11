<?php

namespace App\Modules\Customer;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Useful if you have many fields in a model, instead of using fillable property.
    protected $guarded = [];
}
