<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsGateway
{
    public function __construct(
        protected ?string $endpoint = null,
        protected ?string $apiKey   = null,
        protected ?string $sender   = null,
    ) {
        $this->endpoint ??= config('sms.endpoint');
        $this->apiKey   ??= config('sms.api_key');
        $this->sender   ??= config('sms.sender', 'REGISTRAR');
    }

    public function send(string $mobile, string $message): array
    {
        if (empty($this->endpoint) || empty($this->apiKey)) {
            Log::warning('SMS gateway not configured; message suppressed.', compact('mobile'));
            return ['status' => 'failed', 'ref' => null];
        }

        try {
            $response = Http::timeout(10)->asForm()->post($this->endpoint, [
                'apikey'     => $this->apiKey,
                'number'     => $mobile,
                'message'    => $message,
                'sendername' => $this->sender,
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'sent',
                    'ref'    => (string) ($response->json('message_id') ?? $response->json('id') ?? ''),
                ];
            }

            Log::error('SMS send failed', ['body' => $response->body()]);
            return ['status' => 'failed', 'ref' => null];
        } catch (\Throwable $e) {
            Log::error('SMS exception: ' . $e->getMessage());
            return ['status' => 'failed', 'ref' => null];
        }
    }
}
