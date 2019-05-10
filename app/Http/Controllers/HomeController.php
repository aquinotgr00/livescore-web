<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use DatePeriod;
use DateTime;
use DateInterval;

class HomeController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.thesportsdb.com/api/v1/json/1/',
            'timeout'  => 5.0,
        ]);
    }

    public function index()
    {
        $begin = new DateTime('NOW');
        // $begin->modify('-3 days');
        $end = new DateTime('NOW');
        // $end->modify('+4 days');

        $period = new DatePeriod(
            $begin->modify('-3 days'),
            new DateInterval('P1D'),
            $end->modify('+4 days')
        );

        foreach ($period as $key => $value) {
            $days[] = $value->format('M d');
        }

        $data = $this->getLatestMatches();
        
        if ( isset($data['request']) ) { // if there's error
            $error = 'Server timed out. Please refresh several times.';
            return view('front.index', compact('error'));
        }

        // return $data;
        return view('front.index', compact('data', 'days'));
    }

    public function getLatestMatches()
    {
        $today = date('Y-m-d');
        try {
            $response = $this->client->get('eventsday.php?d='.$today.'&s=Soccer');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error['response'] = Psr7\str($e->getResponse());
            }
            $error['request'] = Psr7\str($e->getRequest());
            return $error;
        }
        
        return json_decode($response->getBody())->events;
    }
}
