<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    public $timestamps = false;

    protected $fillable = ['country_code', 'country_name', 'country_total_confirmed', 'country_total_deaths', 'country_recovered'];
}
