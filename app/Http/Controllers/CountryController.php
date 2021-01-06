<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    public function index()
    {
        $countries = array();

        foreach(Countries::all() as $country){
            $countries[$country["country_code"]] = array(
                "country_name"=> $country["country_name"],
                "country_code"=> $country["country_code"],
                "country_total_confirmed"=> $country["country_total_confirmed"],
                "country_total_deaths"=> $country["country_total_deaths"],
                "country_recovered"=> $country["country_recovered"]
            );
        }
        $countries_json = json_encode($countries, true);

        return response($countries_json, Response::HTTP_OK);
    }

    public function show($country_code)
    {
        $db_country = Countries::where('country_code', $country_code)->first();
        if ($db_country) {
            return response($db_country->jsonSerialize(), Response::HTTP_OK);
        }
        return response("error", Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $country_code = $request->input('country_code');
        $country_name = $request->input('country_name');
        $country_total_confirmed = $request->input('country_total_confirmed');
        $country_total_deaths = $request->input('country_total_deaths');
        $country_recovered = $request->input('country_recovered');

        $db_country = Countries::where('country_code', $country_code)->first();
        if (!$db_country) {
            $db_country = Countries::Create(
                ['country_name' => $country_name,
                'country_code' => $country_code,
                'country_total_confirmed' => $country_total_confirmed,
                'country_total_deaths' => $country_total_deaths,
                'country_recovered' => $country_recovered]
            );

            return response("success", Response::HTTP_CREATED);
        }

        return response("error",Response::HTTP_CONFLICT);
    }

    public function update(Request $request, $country_code)
    {
        $country_name = $request->input('country_name');
        $country_total_confirmed = $request->input('country_total_confirmed');
        $country_total_deaths = $request->input('country_total_deaths');
        $country_recovered = $request->input('country_recovered');

        try {
            $db_country = Countries::where('country_code', $country_code)->firstOrFail()->update(
                [
                    'country_name' => $country_name,
                    'country_total_confirmed' => $country_total_confirmed,
                    'country_total_deaths' => $country_total_deaths,
                    'country_recovered' => $country_recovered
                ]);

            return response("success", Response::HTTP_OK);

        } catch (Exception $e) {
            return response("error", Response::HTTP_NOT_FOUND);
        }
    }
}
