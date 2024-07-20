<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Exports\AppointmentsExport;
use Maatwebsite\Excel\Facades\Excel;


class UsefulController extends Controller
{
    public function getCurrentTime()
    {
        // Define the timezone for your city
        $timezone = 'Africa/Algiers'; // Replace with appropriate timezone if different

        // Create a Carbon instance for the given timezone
        $currentTime = Carbon::now(new \DateTimeZone($timezone));

        $formattedTime = $currentTime->format('H:i:s');

        // Return the current time as a JSON response
        return response()->json([
            'timezone' => $timezone,
            'current_time' => $formattedTime
        ]);
    }

    public function getStatistics()
    {
        $statistics = Appointment::select('specialty_id')
            ->selectRaw('SUM(status = "Pending") as pending_count')
            ->selectRaw('SUM(status = "Delay") as delay_count')
            ->selectRaw('COUNT(DISTINCT CASE WHEN status = "Present" OR status = "Waiting" THEN patient_id END) as present_count')
            ->selectRaw('COUNT(DISTINCT CASE WHEN status = "Completed" THEN patient_id END) as completed_count')
            ->selectRaw('COUNT(DISTINCT patient_id) as total_patients')
            ->with('specialty')
            ->groupBy('specialty_id')
            ->get();
        // Calculate the total counts
        $totalPending = $statistics->sum('pending_count');
        $totalDelay = $statistics->sum('delay_count');
        $totalPresent = $statistics->sum('present_count');
        $totalCompleted = $statistics->sum('completed_count');
        $totalPatients = Appointment::distinct('patient_id')->count('patient_id');


        return response()->json([
            'statistics' => $statistics,
            'totals' => [
                'total_pending' => $totalPending,
                'total_delay' => $totalDelay,
                'total_present' => $totalPresent,
                'total_completed' => $totalCompleted,
                'totalPatients'=>$totalPatients
            ]
        ]);
    }

    public function exportAppointments(Request $request)
    {

        // $fileName = 'appointments.xlsx'; // Specify the file name with the correct extension

        // // Generate the Excel file using the AppointmentsExport class
        // $excelFile = Excel::download(new AppointmentsExport(), $fileName);
    
        // // Return a response to download the generated file
        // return response()->download($excelFile, 'appointments.xlsx', [
        //     'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        // ]);
        return Excel::download(new AppointmentsExport, 'GreenhouseData.csv');
        // return $excelFile->deleteFileAfterSend(true); // Optional: delete the file after sending
    }

    
}
