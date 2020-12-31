<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    public function index()
    {
        return response(Countries::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function show($country_code)
    {
        return response(Countries::where('country_code', $country_code)->first()->jsonSerialize(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data =  $request->json()->all();

        $db_country = Countries::where('country_code', $data["country_code"])->first();
        if (!$db_country) {
            $db_country = Countries::Create(
                ['country_name' => $data["country_name"],
                    'country_code' => $data["country_code"],
                    'country_total_confirmed' => $data["country_total_confirmed"],
                    'country_total_deaths' => $data["country_total_deaths"],
                    'country_recovered' => $data["country_recovered"]]
            );

            return response(Countries::where('country_code', $data["country_code"])->first()->jsonSerialize(), Response::HTTP_CREATED);
        }

        return response(null,Response::HTTP_CONFLICT);
    }

    public function update(Request $request, $country_code)
    {
        $data =  $request->json()->all();

        $db_country = Countries::where('country_code', $country_code)->firstOrFail()->update(
            ['country_total_confirmed' => $data['country_total_confirmed'],
            'country_total_deaths' => $data['country_total_deaths'],
            'country_recovered' => $data['country_recovered']]);

        return response(Countries::where('country_code', $country_code)->first()->jsonSerialize(), Response::HTTP_OK);
    }
}
