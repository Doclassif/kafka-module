<?php

namespace Modules\Kafka\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JsonException;
use Junges\Kafka\Contracts\ConsumerMessage;

class SyncMessagePayload
{
    /**
     * @param  array<string, mixed>  $rules
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function validate(ConsumerMessage $message, array $rules): array
    {
        $payload = $this->payload($message);

        return Validator::make($payload, $rules)->validate();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    private function payload(ConsumerMessage $message): array
    {
        $body = $message->getBody();

        if (is_array($body)) {
            return $body;
        }

        if (is_string($body)) {
            try {
                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                throw ValidationException::withMessages([
                    'message' => 'Kafka message body must be valid JSON.',
                ]);
            }

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        throw ValidationException::withMessages([
            'message' => 'Kafka message body must be a JSON object.',
        ]);
    }
}
