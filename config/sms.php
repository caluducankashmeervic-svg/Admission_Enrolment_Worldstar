<?php

return [
    'endpoint' => env('SMS_ENDPOINT', 'https://api.semaphore.co/api/v4/messages'),
    'api_key'  => env('SMS_API_KEY'),
    'sender'   => env('SMS_SENDER', 'REGISTRAR'),
];
