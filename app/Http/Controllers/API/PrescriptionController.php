<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $prescriptions = [];

        if ($user->isDoctor()) {
            $prescriptions = $user->doctor->prescriptions()
                ->with(['patient.user', 'appointment'])
                ->latest()
                ->get();
        } elseif ($user->isPatient()) {
            $prescriptions = $user->patient->prescriptions()
                ->with(['doctor.user', 'appointment'])
                ->latest()
                ->get();
        } else {
            $prescriptions = Prescription::with(['doctor.user', 'patient.user', 'appointment'])
                ->latest()
                ->get();
        }

        return response()->json($prescriptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'medications' => ['required', 'string'],
            'dosage_instructions' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'valid_until' => ['required', 'date', 'after:today'],
        ]);

        $user = Auth::user();
        if (!$user->isDoctor()) {
            return response()->json(['message' => 'Only doctors can create prescriptions'], 403);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);
        if ($appointment->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($appointment->status !== 'completed') {
            return response()->json(['message' => 'Can only create prescriptions for completed appointments'], 422);
        }

        $prescription = $appointment->prescription()->create([
            'doctor_id' => $user->doctor->id,
            'patient_id' => $appointment->patient_id,
            'medications' => $request->medications,
            'dosage_instructions' => $request->dosage_instructions,
            'notes' => $request->notes,
            'valid_until' => $request->valid_until,
        ]);

        return response()->json([
            'message' => 'Prescription created successfully',
            'prescription' => $prescription->load(['doctor.user', 'patient.user', 'appointment'])
        ], 201);
    }

    public function show(Prescription $prescription)
    {
        return response()->json($prescription->load(['doctor.user', 'patient.user', 'appointment']));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'medications' => ['sometimes', 'string'],
            'dosage_instructions' => ['sometimes', 'string'],
            'notes' => ['nullable', 'string'],
            'valid_until' => ['sometimes', 'date', 'after:today'],
        ]);

        $user = Auth::user();
        if ($user->isDoctor() && $prescription->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $prescription->update($request->only([
            'medications',
            'dosage_instructions',
            'notes',
            'valid_until'
        ]));

        return response()->json([
            'message' => 'Prescription updated successfully',
            'prescription' => $prescription->load(['doctor.user', 'patient.user', 'appointment'])
        ]);
    }

    public function destroy(Prescription $prescription)
    {
        $user = Auth::user();
        if ($user->isDoctor() && $prescription->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $prescription->delete();
        return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
