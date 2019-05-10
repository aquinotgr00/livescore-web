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
    public function index()
    {
        $client = new Client([
            'base_uri' => 'https://www.thesportsdb.com/api/v1/json/1/',
            'timeout'  => 5.0,
        ]);
        $today = date('Y-m-d');
        $begin = new DateTime('NOW');
        $begin->modify('-3 days');
        $end = new DateTime('NOW');
        $end->modify('+4 days');

        $period = new DatePeriod(
            $begin,
            new DateInterval('P1D'),
            $end
        );

        foreach ($period as $key => $value) {
            $days[] = $value->format('M d');
        }

        try {
            $response = $client->request('GET', 'eventsday.php?d='.$today.'&s=Soccer');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error['response'] = Psr7\str($e->getResponse());
            }
            $error['request'] = Psr7\str($e->getRequest());
            return view('front.index', compact('error'));
        }
        $data = json_decode($response->getBody())->events;
        // return $data;
        return view('front.index', compact('data', 'days'));
    }
}
