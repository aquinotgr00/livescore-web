<?php

Route::get('/', 'HomeController@index')->name('homepage');
Route::get('/uefa', 'HomeController@getUEFAMatches')->name('homepage.uefa');
Route::get('/eu-league', 'HomeController@getEULeague')->name('homepage.eu-league');
Route::get('/euro', 'HomeController@getEUROLeague')->name('homepage.euro');
Route::get('/world-cup', 'HomeController@getWorldCup')->name('homepage.world-cup');
Route::get('/match/{date}', 'HomeController@getMatchesByDate')->name('homepage.get-matches-by-date');
Route::get('/country/england', 'HomeController@getEnglandMatches')->name('homepage.get-england-matches');
Route::get('/country-match/{country}', 'HomeController@getCountryLeagues')->name('homepage.get-country-leagues');
Route::get('/country-match-league/{country}/{league}', 'HomeController@getMatchesByCountry')->name('homepage.get-matches-by-country');

