<?php

namespace Modules\Kafka\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Kafka\Database\Factories\KafkaOutboxMessageFactory;
use Modules\Kafka\Enums\KafkaOutboxStatus;

#[UseFactory(KafkaOutboxMessageFactory::class)]
class KafkaOutboxMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic',
        'key',
        'payload',
        'status',
        'attempts',
        'last_error',
        'available_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => KafkaOutboxStatus::class,
            'payload' => 'array',
            'available_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }
}
