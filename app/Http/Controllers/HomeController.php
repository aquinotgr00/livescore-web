<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use DatePeriod;
use DateTime;
use DateInterval;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.thesportsdb.com/api/v1/json/1/',
            'timeout'  => 3.0,
        ]);
        date_default_timezone_set('Asia/Singapore');
        $this->today = date('Y-m-d H:i:s');
    }

    private function saveMatchToDB($path, $data, $category)
    {
        if ($data !== null) {
            foreach ($data as $value) {
                $match = \App\LatestMatches::where('strHomeTeam', $value->strHomeTeam)
                    ->where('strAwayTeam', $value->strAwayTeam)
                    ->where('category', $category)
                    ->first();
                if ($match) {
                    $match->delete();
                }
                $arrayVal = (array) $value;
                // get the origin date and time
                $eventTime = $value->dateEvent.' '.$value->strTime;
                // make a brand new time with those two and convert to local timzeone those time 
                $originTime = new DateTime($eventTime, new \DateTimeZone("Asia/Singapore"));
                // save to database
                $arrayVal['strTime'] = $originTime->format('H:i');
                $arrayVal['category'] = $category;
                \App\LatestMatches::create($arrayVal);
            }
        }

        $lastFetch = \App\LastFetch::where('route', $path)->first();

        if (!$lastFetch) {
            \App\LastFetch::create([
                'date' => $this->today,
                'route' => $path
            ]);
        } else if ($lastFetch) {
            $lastFetch->update(['date' => $this->today]);
        }
    }

    private function saveLeaguesToDB($data)
    {
        if ($data !== null) {
            foreach ($data as $value) {
                $league = \App\CountryLeague::where('strLeague', $value->strLeague)->first();
                if (!$league) {
                    $arrayVal = (array) $value;
                    \App\CountryLeague::create($arrayVal);
                }
            }
        }
    }

    private function getDays()
    {
        $begin = new DateTime('NOW');
        $end = new DateTime('NOW');

        $period = new DatePeriod(
            $begin->modify('-3 days'),
            new DateInterval('P1D'),
            $end->modify('+4 days')
        );

        foreach ($period as $key => $value) {
            $days[$key]['display'] = $value->format('M d');
            $days[$key]['format'] = $value->format('Y-m-d');
        }
        
        return $days;
    }
    
    public function getData($method, $url)
    {
        try {
            $response = $this->client->request($method, $url);
        } catch (RequestException $err) {
            $response['error'] = Psr7\str($err->getRequest());
            return $response;
        }
        
        return json_decode($response->getBody());
    }

    public function getSeasonId($leagueId)
    {
        $seasons = $this->getData('GET', 'search_all_seasons.php?id='.$leagueId);
        if ( !isset($seasons->seasons) && isset($seasons['error']) ) {
            $error = $seasons['error'];
            return view('front.index', compact('error'));
        }
        return end($seasons->seasons)->strSeason;
    }

    public function getAllLeagueEvents($leagueId, $seasonId)
    {
        $data = $this->getData('GET', 'eventsseason.php?id='.$leagueId.'&s='.$seasonId);
        if ( !isset($data->events) && isset($data['error']) ) {
            return $data;
        }
        return $data->events;
    }

    private function getLeaguesIdByCountry($country)
    {
        $data = $this->getData('GET', 'search_all_leagues.php?c='.$country.'&s=Soccer');
        if ( !isset($data->countrys) && isset($data['error']) ) {
            return $data;
        }
        return $data->countrys;
    }






    public function index(Request $request)
    {
        $days = $this->getDays();

        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $request->path())->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $data = $this->getData('GET', 'eventsday.php?d='.date('Y-m-d', strtotime($this->today)).'&s=Soccer'); // fetch the data

            if (!is_object($data)) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                if($data->events === null) { // if there are no matches today
                    $this->saveMatchToDB($route, null, 'all');
                }
                $this->saveMatchToDB($route, $data->events, 'all');
            }

        }


        // get the latest matches from db
        $data = \App\LatestMatches::where('dateEvent', date('Y-m-d', strtotime($this->today)))
            ->where('category', 'all')
            ->get(); 
        if(count($data) == 0) { // if today matches is 0 return messages and days.
            $error = 'There are no matches today.';
            return view('front.index', compact('error', 'days'));
        }


        return view('front.index', compact('data', 'days'));
    }

    public function getMatchesByDate(Request $request, $date)
    {
        $days = $this->getDays();
        
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $request->path())->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $data = $this->getData('GET', 'eventsday.php?d='.$date.'&s=Soccer'); // fetch the data

            if (!property_exists($data, 'events') && isset($data['error'])) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                if($data->events === null) { // if there are no matches today
                    $this->saveMatchToDB($route, null, 'all');
                }
                $this->saveMatchToDB($route, $data->events, 'all');
            }

        }
        
        // get the latest matches from db
        $data = \App\LatestMatches::where('dateEvent', $date)
            ->where('category', 'all')
            ->get(); 
        if(count($data) == 0) { // if today matches is 0 return messages and days.
            $error = 'There are no matches this day.';
            return view('front.index', compact('error', 'days'));
        }

        return view('front.index', compact('data', 'days'));
    }

    public function getUEFAMatches(Request $request)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            // get the latest season id of the league
            $seasonId = $this->getSeasonId(4480);
            $data = $this->getAllLeagueEvents(4480, $seasonId);
            
            if (isset($data['error'])) {
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            $this->saveMatchToDB($route, $data, 'uefa');

        }
        
        $data = \App\LatestMatches::where('category', 'uefa')
            ->orderBy('intRound', 'desc')
            ->get(); 

        return view('front.index', compact('data'));
    }

    public function getEULeague(Request $request)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            // get the latest season id of the league
            $seasonId = $this->getSeasonId(4481);
            $data = $this->getAllLeagueEvents(4481, $seasonId);
            
            if (isset($data['error'])) {
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            $this->saveMatchToDB($route, $data, 'eu-league');

        }
        
        $data = \App\LatestMatches::where('category', 'eu-league')
            ->orderBy('intRound', 'desc')
            ->get();
            
        return view('front.index', compact('data'));
    }

    public function getEUROLeague(Request $request)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $data = $this->getData('GET', 'eventsseason.php?id=4502');

            if (!property_exists($data, 'events') && isset($data['error'])) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                $this->saveMatchToDB($route, $data->events, 'euro');
            }

        }
        
        $data = \App\LatestMatches::where('category', 'euro')
            ->orderBy('intRound', 'desc')
            ->get();

        return view('front.index', compact('data'));
    }

    public function getWorldCup(Request $request)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $data = $this->getData('GET', 'eventspastleague.php?id=4429');

            if (!property_exists($data, 'events') && isset($data['error'])) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                $this->saveMatchToDB($route, $data->events, 'world-cup');
            }

        }
        
        $data = \App\LatestMatches::where('category', 'world-cup')
            ->orderBy('intRound', 'desc')
            ->get();

        return view('front.index', compact('data'));
    }

    public function getCountryLeagues(Request $request, $country)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $leagueIds = $this->getLeaguesIdByCountry($country);

            // if the requested country is england then change the first league to english premier league
            if ( !isset($leagueIds['error']) ) {
                if ( $country == 'england' ) {
                    while( $leagueIds[0]->strLeague !== 'English Premier League' ) {
                        $temp = array_shift($leagueIds);
                        $leagueIds[] = $temp;
                    }
                }

                $data = $this->getData('GET', 'eventspastleague.php?id='.$leagueIds[0]->idLeague);
            }

            if (!property_exists($data, 'events') && isset($data['error'])) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                $this->saveMatchToDB($route, $data->events, 'country-matches-'. $leagueIds[0]->idLeague);
                $this->saveLeaguesToDB($leagueIds);
            }

        }
        
        $leagueIds = \App\CountryLeague::where('strCountry', $country)->get();

        $data = \App\LatestMatches::where('category', 'country-matches-'. $leagueIds[0]->idLeague)
            ->get();
        
        return view('front.index', compact('data', 'country', 'leagueIds'));
    }

    public function getMatchesByCountry(Request $request, $country, $leagueId)
    {
        // check the last fetch to the api depends on the url
        $route = $request->path();
        $lastFetch = \App\LastFetch::where('route', $route)->first();
        if ($lastFetch) {
            $lastFetchDate = new DateTime($lastFetch->date);
            $todayDate = new DateTime($this->today);
            $interval = $todayDate->diff($lastFetchDate);
        }
        if ( !$lastFetch || $interval->d >= 1 || $interval->h >= 6) { // if there are no fetch or the fetch is not today

            $data = $this->getData('GET', 'eventspastleague.php?id='.$leagueId);
            $leagueIds = $this->getLeaguesIdByCountry($country);

            if (!property_exists($data, 'events') && isset($data['error'])) { // if there's an error
                $error = 'Server timed out. Please refresh several times.';
                return view('front.index', compact('error'));
            }
            else if ( property_exists($data, 'events') ) {
                $this->saveMatchToDB($route, $data->events, 'country-matches-'. $leagueId);
                // $this->saveLeaguesToDB($leagueIds);
            }

        }
        
        $leagueIds = \App\CountryLeague::where('strCountry', $country)->get();
        $data = \App\LatestMatches::where('category', 'country-matches-'. $leagueId)
            ->get();

        return view('front.index', compact('data', 'country', 'leagueIds'));
    }

    public function getEnglandMatches(Request $request)
    {
        $data = $this->getData('GET', 'eventspastleague.php?id=4328');
        return view('front.index', compact('data', 'country', 'leagueIds'));
    }
}
