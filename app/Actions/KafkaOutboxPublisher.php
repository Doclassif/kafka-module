<?php

namespace Modules\Kafka\Actions;

use Modules\Kafka\Enums\KafkaOutboxStatus;
use Modules\Kafka\Jobs\PublishKafkaOutboxMessage;
use Modules\Kafka\Models\KafkaOutboxMessage;

class KafkaOutboxPublisher
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function publish(string $topic, array $payload, ?string $key = null, bool $dispatch = false): KafkaOutboxMessage
    {
        $message = KafkaOutboxMessage::create([
            'topic' => config('kafka.topic_prefix', app()->environment()).'.'.$topic,
            'key' => $key,
            'payload' => $payload,
            'status' => KafkaOutboxStatus::PENDING,
            'available_at' => now(),
        ]);

        if ($dispatch)
            PublishKafkaOutboxMessage::dispatch($message->id)->afterCommit();

        return $message->refresh();
    }
}
