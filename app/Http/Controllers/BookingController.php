<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
   public function booking(Request $request)
{
        $dates = [
        ['day' => '8', 'label' => 'Mon'],
        ['day' => '9', 'label' => 'Tue'],
        ['day' => '10', 'label' => 'Wed'],
        ['day' => '11', 'label' => 'Thu'],
        ['day' => '12', 'label' => 'Fri'],
    ];

    $times = ['10:00 PM', '12:00 PM', '05:00 AM', '10:00 AM', '11:30 AM'];

    $selectedDate = $request->get('date', 0);
    $selectedTime = $request->get('time', '11:30 AM');

    return view('appointment.booking', compact('dates', 'times', 'selectedDate', 'selectedTime')); 
}

public function listDokter()
{
    return view('appointment.list-dokter'); 
}

public function detailDokter()
{
    return view('appointment.detail-dokter');
}

public function uploadFile()
{
    return view('appointment.upload-file');

}

public function filterDokter()
{

    
    return view('appointment.filter'); 

}

}