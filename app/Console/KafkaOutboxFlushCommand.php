<?php

namespace Modules\Kafka\Console;

use Illuminate\Console\Command;
use Modules\Kafka\Services\KafkaOutboxDispatcher;

class KafkaOutboxFlushCommand extends Command
{
    protected $signature = 'kafka-outbox:flush {--limit=100}';

    protected $description = 'Dispatch jobs for pending Kafka outbox messages';

    public function handle(KafkaOutboxDispatcher $dispatcher): int
    {
        $dispatched = $dispatcher->dispatchPending((int) $this->option('limit'));

        $this->info("Dispatched {$dispatched} Kafka outbox job(s).");

        return self::SUCCESS;
    }
}
