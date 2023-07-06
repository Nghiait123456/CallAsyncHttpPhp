<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Spatie\Async\Pool;


class AsyncSpatieController extends Controller
{
    public function callAsyncAPI()
    {
        for ($i =0; $i < 10; $i++) {
            $urls[$i]= 'https://www.google.com/';
        }

        $pool = Pool::create();
        $tempResults = [];

        foreach ($urls as $key =>$url) {
            $index = $key;
            $pool[] = async(function () use ($url) {
                try {
                    $client = new Client();
                    $response = $client->get($url);

                    $chunkSize = 1024;

//                    throw new \Exception("test");
                    $stream = $response->getBody()->detach();
                    $responseData = '';

                    while (!feof($stream)) {
                        $chunk = fread($stream, $chunkSize);
                        $responseData .= $chunk;

                        if (strlen($responseData) >= $chunkSize) {
                            return $responseData;
                        }
                    }

                    fclose($stream); // Đóng luồng dữ liệu

                    return $responseData;
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            })->then(function ($response) use (&$tempResults, $index) {
                $tempResults[$index] = $response;
            });
        }



        $responses = $pool->wait();

        dd($tempResults);

        // Xử lý kết quả tổng hợp
        $combinedResult = '';

        foreach ($responses as $response) {
            $combinedResult .= $response;
        }

        return $combinedResult;
    }
}
