<?php

namespace App\Http\Controllers;

use App\Events\EmergencyCall;
use App\Http\Controllers\Controller;
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
        
            EmergencyCall::dispatch($user, $uuid);
        }

        return response()->json(['message' => $message], $code);
    }
}
