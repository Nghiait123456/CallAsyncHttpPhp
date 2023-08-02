<?php

namespace App\Http\Controllers;



class RDKafKaController extends Controller
{
    public function sendRDKafka(): bool
    {
        /**
         * $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($dataPushFeed));
         * fc produce() php rd kafka return void and no ex, dont have way detect produce error or success
         * this is solution:
         * 1)  It is advised to call poll at regular intervals to serve callbacks. In php-rdkafka:3.x
         * poll was also called during shutdown, so not calling it in regular intervals might
         * lead to a slightly longer shutdown. The example below polls until there are no more events in the queue:
         *
         * $producer->produce(...);
         * while ($producer->getOutQLen() > 0) {
         * $producer->poll(1);
         * }
         * 2) Add solution timeout 
         */
        $brokers = "test";
        $topic = "topic";
        $conf = new \RdKafka\Conf();
        $conf->set('debug','all');
        $producer = new \RdKafka\Producer($conf);
        $producer->addBrokers($brokers);
        $topic = $producer->newTopic($topic);

        $dataPushFeed = [
            'session_id' => 12123131,
            'shop_code' => "TEST",
            'shop_id' => 1,
            'session_status' => "test",
            'event' => Constant::BBS_SESSION_EVENT['UPDATE'],
        ];
        dump($producer->getOutQLen());
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, "test messsage");

        // set timeout
        $timeout = 5000; // 5 giây
        $start = microtime(true);
        $end = $start + ($timeout / 1000);

        while ($producer->getOutQLen() > 0 && microtime(true) < $end) {
            $producer->poll(100); // check again 100 ms
        }

        //check end times
        if ($producer->getOutQLen() > 0) {
            // Hủy việc gửi và báo lỗi
            $producer->flush(0); // Hủy gửi sau 2 giây
            echo "Send cancelled due to timeout.\n";
            return false;
        }

        return true;
    }
}
