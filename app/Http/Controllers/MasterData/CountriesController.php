<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Support\Facades\Redis;

class CountriesController extends Controller {

    /**
     * @group Master Data
     * Countries List Mini
     * Countries List used for registration screen where we show country flag and ISD code fo country.
     *
     * @responseFile 200 responses/MasterData/countries-200.json
     * @return mixed
     */
    public function index() {
        $countries = '';
//s        $countries = Redis::get('countries-reg');
        if (!$countries) {
            $countriesList = Country::select('countryName', 'countryCode', 'isdCode', 'currencyCode', 'currencyName', 'currencySymbol')->get();
            Redis::set('countries-reg', $countriesList);
        }
        $countries = ['countries' => json_decode(Redis::get('countries-reg'))];
        return response()->success($countries, 'E_NO_ERRORS');
    }

}
