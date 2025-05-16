<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\NotificationController;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new medical report
    public function createReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|string',
            'summary' => 'required|string',
            // Add other fields as needed
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $report = Report::create($request->all());

        // Send notification to patient
        $this->notificationController->sendNotification([
            'user' => $request->patient_id,
            'message' => 'A new medical report has been created for you.',
            'type' => 'report',
        ]);

        return response()->json([
            'message' => 'Report created successfully',
            'data' => $report,
        ], Response::HTTP_CREATED);
    }

    // Get all reports
    public function getAllReports()
    {
        $reports = Report::with(['patient', 'doctor'])->get();

        return response()->json([
            'total' => $reports->count(),
            'data' => $reports,
        ], 200);
    }

    // Get report by ID
    public function getReportById($id)
    {
        $report = Report::with(['patient', 'doctor'])->find($id);

        if (!$report) {
            throw new NotFoundException('Report not found');
        }

        return response()->json($report, 200);
    }

    // Get reports by patient ID
    public function getReportsByPatient($patientId)
    {
        $reports = Report::where('patient_id', $patientId)
            ->with('doctor')
            ->get();

        if ($reports->isEmpty()) {
            throw new NotFoundException('No reports found for this patient');
        }

        return response()->json($reports, 200);
    }

    // Get reports by doctor ID
    public function getReportsByDoctor($doctorId)
    {
        $reports = Report::where('doctor_id', $doctorId)
            ->with('patient')
            ->get();

        if ($reports->isEmpty()) {
            throw new NotFoundException('No reports found for this doctor');
        }

        return response()->json($reports, 200);
    }

    // Update a report
    public function updateReport(Request $request, $id)
    {
        $report = Report::find($id);

        if (!$report) {
            throw new NotFoundException('Report not found to update');
        }

        $report->update($request->all());

        return response()->json([
            'message' => 'Report updated successfully',
            'data' => $report,
        ], 200);
    }

    // Delete a report
    public function deleteReport($id)
    {
        $report = Report::find($id);

        if (!$report) {
            throw new NotFoundException('Report not found to delete');
        }

        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully',
            'data' => $report,
        ], 200);
    }
}
