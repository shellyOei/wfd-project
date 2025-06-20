<?php

namespace App\Http\Controllers;

use App\Events\EmergencyCall;
use App\Http\Controllers\Controller;
use App\Models\EmergencyCallQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmergencyController extends Controller
{
    function viewUser() {

        $data['no_nav'] = true;

        return view('user.emergency', $data);
    }

    function viewAdmin() {

        return view('admin.emergency');
    }

    function requestEmergencyCall(Request $request) 
    {

        $uuid = $request->input('uuid');
        

        $message = "Calling...";
        $code = 200;

        if(!$uuid) {
            $message = "Something went wrong. Please try again.";
            $code = 401;
        } else {
            $user = Auth::guard('user')->user();
            // dd($user);

            $this->saveRequestEntry($uuid, $user ? $user['id'] : null);
        
            // EmergencyCall::dispatch($user, $uuid);
            EmergencyCall::dispatch();
        }

        return response()->json(['message' => $message], $code);
    }


    function saveRequestEntry (string $frontendUuid, $userId) {
        $data = [
            'frontend_uuid' => $frontendUuid,
            'user_id' => $userId ?? null,
        ];

        EmergencyCallQueue::create($data);
    }


    function getNextCall () {
        $emergencyCalls = EmergencyCallQueue::with('user.patients')
                        ->where('created_at', '>=', Carbon::now()->subMinutes(20))
                        ->whereNot('is_served', 1)
                        ->orderBy('created_at')->get();

        $onLineCount = sizeOf($emergencyCalls);
        $nextCall = isset($emergencyCalls[0]) ? $emergencyCalls[0] : 0;
        

        $data = [
            'onLineCount' => $onLineCount,
            'nextCall' => $nextCall,
        ];

        return $data;
    }

    function countOnLine () {
        $onLineCount = EmergencyCallQueue::with('user')
                        ->where('created_at', '>=', Carbon::now()->subMinutes(20))
                        ->whereNot('is_served', 1)
                        ->orderBy('created_at')->count();

        return $onLineCount;
    }

}
