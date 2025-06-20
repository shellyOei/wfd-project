<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Zego\ZegoServerAssistant;

class ZegoCloudController extends Controller
{
    protected $zegoCloudService;

    public function __construct(ZegoServerAssistant $zegoCloudService)
    {
        $this->zegoCloudService = $zegoCloudService;
    }

    public function getToken(Request $request)
    {
        $userId = $request->input('frontend_uuid'); 
        $appId = env('ZEGOCLOUD_APP_ID');
        $secret = env('ZEGOCLOUD_SERVER_SECRET');
        $effectiveTimeInSeconds = 20 * 60; // 20 minutes


        $token = $this->zegoCloudService->generateToken04($appId, $userId, $secret, $effectiveTimeInSeconds, '{}');


        if ($token) {
            return response()->json([
                'appID' => (int)env('ZEGOCLOUD_APP_ID'),
                'userID' => (string)$userId,
                'userName' => 'User_' . $userId,
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Failed to generate ZegoCloud token'], 500);
    }
}
