<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\User;

class EventController extends Controller
{
    public function archiveEvent($eventId)
{
    // Archive all appointments
    $appointments = Appointment::where('event_id', $eventId)->get();
    foreach ($appointments as $appointment) {
        $appointment->archive();
    }

    // Archive all specialties
    $specialties = Specialty::where('event_id', $eventId)->get();
    foreach ($specialties as $specialty) {
        $specialty->archive();
    }

    // Archive all users linked to the event (if needed)
    $users = User::where('event_id', $eventId)->get();
    foreach ($users as $user) {
        $user->archive();
    }

    return response()->json(['message' => 'Event archived successfully.']);
}

}
