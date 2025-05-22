<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;

class PatientController extends Controller
{
    // Get all patients
    public function getAllPatients()
    {
        $patients = Patient::with('user')->get();
        return response()->json(['patients' => $patients], 200);
    }

    // Get one patient by ID
    public function getPatientById($id)
    {
        $patient = Patient::with('user')->find($id);
        if (!$patient) {
            throw new NotFoundException('Patient not found');
        }
        return response()->json(['patient' => $patient], 200);
    }

    // Create a new patient
    public function createPatient(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user' => 'required|string|exists:users,id',
            'bloodType' => 'nullable|string',
            'emergencyContact' => 'nullable|array',
            'insurance' => 'nullable|array',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        if ($validated->fails()) {
            throw new BadRequestException($validated->errors()->first());
        }

        $user = User::find($request->user);
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $existingPatient = Patient::where('user_id', $user->id)->first();
        if ($existingPatient) {
            throw new BadRequestException('Patient record already exists for this user');
        }

        // Assign 'patient' role if not already
        if ($user->role !== 'patient') {
            $user->role = 'patient';
            $user->save();
        }

        $patient = Patient::create([
            'user_id' => $user->id,
            'bloodType' => $request->bloodType,
            'emergencyContact' => $request->emergencyContact,
            'insurance' => $request->insurance,
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        return response()->json(['patient' => $patient], 201);
    }

    // Update patient details
    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            throw new NotFoundException('Patient not found');
        }

        $patient->update($request->all());

        return response()->json(['updatedPatient' => $patient->fresh('user')], 200);
    }

    // Delete patient
    public function deletePatient($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            throw new NotFoundException('Patient not found');
        }

        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully',
            'patient' => $patient
        ], 200);
    }

    // Get patient by user ID
    public function getPatientByUserId($userId)
    {
        $patient = Patient::where('user_id', $userId)->with('user')->first();

        if (!$patient) {
            throw new NotFoundException('Patient not found for the given user');
        }

        return response()->json(['patient' => $patient], 200);
    }
}
