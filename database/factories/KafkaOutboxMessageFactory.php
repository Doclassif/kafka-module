<?php

namespace Modules\Kafka\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Kafka\Enums\KafkaOutboxStatus;
use Modules\Kafka\Models\KafkaOutboxMessage;

/**
 * @extends Factory<KafkaOutboxMessage>
 */
class KafkaOutboxMessageFactory extends Factory
{
    protected $model = KafkaOutboxMessage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic' => fake()->slug(2).'.sync',
            'key' => (string) fake()->numberBetween(1, 999999),
            'payload' => [
                'id' => fake()->numberBetween(1, 999999),
                'event' => fake()->word(),
            ],
            'status' => KafkaOutboxStatus::PENDING,
            'attempts' => 0,
            'last_error' => null,
            'available_at' => now(),
            'sent_at' => null,
        ];
    }

    public function sent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => KafkaOutboxStatus::SENT,
            'sent_at' => now(),
            'last_error' => null,
        ]);
    }
}
