<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    protected $table = 'customer_interactions';
    protected $fillable = ['phone_number', 'is_first_interaction'];
}
