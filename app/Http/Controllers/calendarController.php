<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class calendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $api_key = 'f4f6baf0bafc9ca949cca66d53c8c85fadadb2aa';

        $country = 'SY';
        $year = 2022;
        $month = 12;

        $client = new Client();

        $response = $client->request('GET', "https://calendarific.com/api/v2/holidays?api_key={$api_key}&country={$country}&year={$year}&month={$month}");

        $holidays = json_decode($response->getBody(), true)['response']['holidays'];

        return response()->json($holidays);


    }
}