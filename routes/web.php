<?php
use GuzzleHttp\Client;

Route::get('/', function () {
    return view('front.index');
});

Route::get('/test', function () {
    $client = new Client(['base_uri' => 'https://www.thesportsdb.com/api/v1/json/1/']);
    $response = $client->request('GET', 'search_all_leagues.php?c=England&s=Soccer');
    return $response->getBody();
});
