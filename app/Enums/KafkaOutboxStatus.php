<?php

namespace Modules\Kafka\Enums;

enum KafkaOutboxStatus: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
}
