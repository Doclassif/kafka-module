<?php

namespace Modules\Kafka\Services;

use Modules\Kafka\Enums\KafkaOutboxStatus;
use Modules\Kafka\Jobs\PublishKafkaOutboxMessage;
use Modules\Kafka\Models\KafkaOutboxMessage;

class KafkaOutboxDispatcher
{
    public function dispatchPending(int $limit = 100): int
    {
        $dispatched = 0;

        KafkaOutboxMessage::where('status', KafkaOutboxStatus::PENDING)
            ->where(function ($query) {
                $query
                    ->whereNull('available_at')
                    ->orWhere('available_at', '<=', now());
            })
            ->whereRaw("topic like '%?.", config('kafka.topic_prefix', app()->environment()))
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->each(function (KafkaOutboxMessage $message) use (&$dispatched): void {
                PublishKafkaOutboxMessage::dispatch($message->id);
                $dispatched++;
            });

        return $dispatched;
    }
}
