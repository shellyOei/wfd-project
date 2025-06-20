<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZegoCloudService
{
    protected $appId;
    protected $serverSecret;

    public function __construct()
    {
        $this->appId = env('ZEGOCLOUD_APP_ID');
        $this->serverSecret = env('ZEGOCLOUD_SERVER_SECRET');
    }

    public function generateToken(string $userId, int $expireTime = 7200) // 2 hours default
    {
        $url = 'https://zego-token.zegocloud.com/api/room/get_token'; // ZegoCloud's token generation API

        // Prepare the payload for the token generation request
        $payload = [
            'app_id' => (int)$this->appId,
            'id_name' => $userId,
            'secret' => $this->serverSecret,
            'expire' => $expireTime,
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['code'] === 0 && isset($data['data']['token'])) {
                return $data['data']['token'];
            }

            // Handle error if token generation fails
            // Log::error('ZegoCloud Token Generation Failed: ' . json_encode($data));
            return null;

        } catch (\Exception $e) {
            // Log::error('ZegoCloud Token Generation Exception: ' . $e->getMessage());
            return null;
        }
    }
}

?>