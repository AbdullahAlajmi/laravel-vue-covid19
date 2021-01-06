<?php

use App\Models\Countries;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $countries = array();

    foreach(Countries::all() as $country){
        $countries[$country["country_code"]] = array("country_name"=> $country["country_name"],
            "country_total_confirmed"=> $country["country_total_confirmed"],
            "country_total_deaths"=> $country["country_total_deaths"],
            "country_recovered"=> $country["country_recovered"]
        );
    }
    $countries_json = json_encode($countries, true);

    return view('index',compact("countries_json"));
});
