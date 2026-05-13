<?php

namespace Modules\Kafka\Console;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;

abstract class KafkaConsumer extends Command
{   
    /** @return class-string */
    abstract protected function handler(): callable|string;
    
    /** @return string[] */
    abstract protected function topics(): array;

    protected function autoCommit()
    {
        return true;
    }
    /** @return string[]|null */
    protected function brokers(): ?array
    {
        return null;
    }

    /** @return string|null */
    protected function groupId(): ?string
    {
        return null;
    }

    public function handle(): int
    {
        $handlerClass = $this->handler();

        Kafka::consumer()
            ->subscribe($this->topics())
            ->withHandler(app($handlerClass))
            ->withConsumerGroupId($this->groupId())
            ->withBrokers($this->brokers())
            ->withAutoCommit($this->autoCommit())
            ->build()
            ->consume();

        return self::SUCCESS;
    }
}