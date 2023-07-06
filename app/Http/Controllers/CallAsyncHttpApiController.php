<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Promise;
class CallAsyncHttpApiController extends Controller
{
    public function callSyncHttp(Request $request) {
        $start = microtime(true);

        $client = new Client();

        $promises = [];
        for ($i = 0; $i < 500; $i++) {
            $index = 'api' . (string)$i;
            $promises[$index] = $client->requestAsync("GET", 'https://www.google.com/');
        }

        $results = Promise\Utils::all($promises)->wait();

        $end = microtime(true);
        $executionTime = $end - $start;
        echo "Execution time: " . $executionTime . " seconds\n";
        dd($results, $executionTime);
    }
}
