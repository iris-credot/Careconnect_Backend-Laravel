<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\NotificationController;
use Symfony\Component\HttpFoundation\Response;

class HealthController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new health record
    public function createHealthRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|string',
            'chronic_diseases' => 'nullable|array',
            'allergies' => 'nullable|array',
            'medications' => 'nullable|array',
            'surgeries' => 'nullable|array',
            'family_history' => 'nullable|array',
            'lifestyle' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        // Check if health record already exists for patient
        $existing = Health::where('patient_id', $request->patient_id)->first();
        if ($existing) {
            throw new BadRequestException('Health record already exists for this patient');
        }

        $health = Health::create([
            'patient_id' => $request->patient_id,
            'chronic_diseases' => $request->chronic_diseases,
            'allergies' => $request->allergies,
            'medications' => $request->medications,
            'surgeries' => $request->surgeries,
            'family_history' => $request->family_history,
            'lifestyle' => $request->lifestyle,
            'updatedAt' => now(),
        ]);

        // Send notification
        $this->notificationController->sendNotification([
            'user' => $request->patient_id,
            'message' => 'A new health record has been created for you.',
            'type' => 'health',
        ]);

        return response()->json([
            'message' => 'Health record created',
            'data' => $health,
        ], Response::HTTP_CREATED);
    }

    // Get all health records (admin)
    public function getAllHealthRecords()
    {
        $records = Health::with('patient')->get();
        return response()->json(['data' => $records], 200);
    }

    // Get health record by patient ID
    public function getHealthByPatient($patientId)
    {
        $record = Health::with('patient')->where('patient_id', $patientId)->first();

        if (!$record) {
            throw new NotFoundException('Health record not found');
        }

        return response()->json(['data' => $record], 200);
    }

    // Update health record by patient ID
    public function updateHealthRecord(Request $request, $patientId)
    {
        $record = Health::where('patient_id', $patientId)->first();

        if (!$record) {
            throw new NotFoundException('Health record not found');
        }

        $validator = Validator::make($request->all(), [
            'chronic_diseases' => 'nullable|array',
            'allergies' => 'nullable|array',
            'medications' => 'nullable|array',
            'surgeries' => 'nullable|array',
            'family_history' => 'nullable|array',
            'lifestyle' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed: ' . $validator->errors()->first());
        }

        $record->update($request->all());
        $record->updatedAt = now();
        $record->save();

        return response()->json([
            'message' => 'Health record updated',
            'data' => $record,
        ], 200);
    }

    // Delete health record by patient ID
    public function deleteHealthRecord($patientId)
    {
        $record = Health::where('patient_id', $patientId)->first();

        if (!$record) {
            throw new NotFoundException('Health record not found');
        }

        $record->delete();

        return response()->json([
            'message' => 'Health record deleted',
        ], 200);
    }
}
