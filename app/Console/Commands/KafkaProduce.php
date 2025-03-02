<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaProduce extends Command
{
    protected $signature = 'kafka:produce';
    protected $description = 'Send predefined message to Kafka';

    public function handle()
    {
        try {
            $topic = 'user-events';
            $brokers = config('kafka.brokers');

            $messageData = [
                'event_id' => uniqid(),
                'event_type' => 'user.registered',
                'payload' => [
                    'user_id' => 123,
                    'email' => 'test@example.com',
                    'created_at' => now()->toDateTimeString()
                ]
            ];

            $message = new Message(
                headers: ['service' => 'laravel-cli'],
                body: $messageData,
                key: 'registration-event'
            );

            Kafka::publish($brokers)
                ->onTopic($topic)
                ->withMessage($message)
                ->send();

            $this->info("Message sent to topic [{$topic}]:");
            $this->line(json_encode($messageData, JSON_PRETTY_PRINT));

            return 0;

        } catch (\Exception $e) {
            $this->error("Error sending message: {$e->getMessage()}");
            return 1;
        }
    }
}
