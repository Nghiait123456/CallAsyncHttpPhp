<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Revolt\EventLoop;
use GuzzleHttp\Client;

class RevoltController extends Controller
{
    //
    public function testQuery(Request $request)
    {
        $startTime = Carbon::now();
        $restApi = [];
        for ($i = 0; $i < 50; $i++) {
            $restApi[$i] = "https://www.google.com.vn";
        }

        $responseRestApi = [];
        foreach ($restApi as $key => $value) {
            $index = $key;
            $v = $value;
            EventLoop::defer(function () use ($index, $v, &$responseRestApi): void {
                $client = new Client();
                $res = $client->get($v);
                $responseRestApi[$index] = $res;
            });
        }

        EventLoop::run();

//        $responseQuery = [];
//        foreach ($restApi as $key => $value) {
//            $index = $key;
//            EventLoop::defer(function () use ($index, &$responseQuery): void {
//                $r = DB::table('addresses')->get();
//                $responseQuery[$index] = $r;
//            });
//        }
//        EventLoop::run();
        $endTime = Carbon::now();

        dd("done", $responseRestApi, $endTime->diffInMilliseconds($startTime));
    }
}
