<?php

namespace Modules\Kafka\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Kafka\Console\KafkaOutboxFlushCommand;
use Modules\Kafka\Services\KafkaOutboxDispatcher;
use Nwidart\Modules\Support\ModuleServiceProvider;

class KafkaServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Kafka';

    protected string $nameLower = 'kafka';

    /**
     * @var string[]
     */
    protected array $commands = [
        KafkaOutboxFlushCommand::class,
    ];

    public function boot(): void
    {
        parent::boot();

        // $this->app->booted(function (): void {
        //     $schedule = $this->app->make(Schedule::class);

        //     $schedule->call(fn () => $this->app->make(KafkaOutboxDispatcher::class)->dispatchPending())
        //         ->everyMinute()
        //         ->withoutOverlapping();
        // });
    }
}
