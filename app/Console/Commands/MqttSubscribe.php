<?php

namespace App\Console\Commands;

use App\Services\MqttService;
use Illuminate\Console\Command;

class MqttSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt-subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqtt = new MqttService();

        // Subscribe ke topik di sesuaikan!
        $mqtt->subscribe('irez/send', function ($topic, $message) use ($mqtt) {
            
            $this->info("Pesan diterima dari $topic: $message");
            if($message == "oke") {
                $mqtt->publish('irez/reply', "Oke pesan udah ada"); // publish pesan ke broker (contoh)
            }
            
        });
        
        $this->info('Menunggu pesan dari broker...');
        // $mqtt->loop(60); // loop selama 60 detik lalu berhenti
        // $mqtt->disconnect();

        while (true) {
            $mqtt->loop(1);
            sleep(2);
        }
    }
}
