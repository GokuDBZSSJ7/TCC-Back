<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $city = City::all();
        return response()->json($city);
    }

    public function getByUf($stateId){
        $cities = City::where('id_estado', $stateId)->get();
        return response()->json($cities);
    }
}