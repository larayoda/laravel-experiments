<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class KafkaConsume extends Command
{
    protected $signature = 'kafka:consume';
    protected $description = 'Consume messages from Kafka';

    public function handle()
    {
        // Конфигурация (все параметры заданы явно)
        $config = [
            'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),
            'topics' => ['user-events'],
            'groupId' => 'laravel-consumer-group',
            'securityProtocol' => 'PLAINTEXT'
        ];

        $this->info("Starting Kafka consumer for topics: " . implode(', ', $config['topics']));

        try {
            $consumer = Kafka::consumer($config['topics'], $config['groupId'], $config['brokers'])
                ->withAutoCommit()
                ->withSecurityProtocol($config['securityProtocol'])
                ->withHandler(function(ConsumerMessage $message) {
                    $this->line("[{$message->getTimestamp()}] Topic: {$message->getTopicName()}");
                    $this->line("Key: {$message->getKey()}");
                    $this->line("Headers: " . json_encode($message->getHeaders()));
                    $this->line("Body: " . json_encode($message->getBody()));
                    $this->line(str_repeat('-', 50));
                })
                ->build();

            $consumer->consume();

        } catch (\Exception $e) {
            $this->error("Consumer error: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
}
