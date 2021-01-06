<?php

use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Models;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/country', 'CountryController', [
    'except' => ['edit', 'create', 'destroy']
]);


Route::get('/fill_data', function (Request $request) {

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://api.covid19api.com/summary', ['verify' => false]);

    $response_data = $response->getBody()->getContents();
    $response_json = json_decode($response_data, true);


    foreach ($response_json['Countries'] as $country) {
        $country_name = $country['Country'];
        $country_code = $country['CountryCode'];
        $country_total_confirmed = (string) $country['TotalConfirmed'];
        $country_total_deaths = (string) $country['TotalDeaths'];
        $country_recovered = (string) $country['TotalRecovered'];

        $db_country = Models\Countries::updateOrCreate(
            ['country_code' => $country_code],
            ['country_name' => $country_name,
            'country_code' => $country_code,
            'country_total_confirmed' => $country_total_confirmed,
            'country_total_deaths' => $country_total_deaths,
            'country_recovered' => $country_recovered]
        );
    }

    $countries = array();

    foreach(Countries::all() as $country){
        $countries[$country["country_code"]] = array(
            "country_code"=> $country["country_code"],
            "country_name"=> $country["country_name"],
            "country_total_confirmed"=> $country["country_total_confirmed"],
            "country_total_deaths"=> $country["country_total_deaths"],
            "country_recovered"=> $country["country_recovered"]
        );
    }
    $countries_json = json_encode($countries, true);

    return response($countries_json, Response::HTTP_OK);
});
