<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/appointment/create",
 *     summary="Create an Appointment Details",
 *     tags={"Appointments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"doctor_id", "patient_id", "appointment_date", "time_slot"},
 *             @OA\Property(property="doctor_id", type="integer", example=1),
 *             @OA\Property(property="patient_id", type="integer", example=1),
 *             @OA\Property(property="appointment_date", type="string", format="date", example="2024-03-20"),
 *             @OA\Property(property="time_slot", type="string", example="10:00 AM"),
 *             @OA\Property(property="notes", type="string", example="Regular checkup"),
 *             @OA\Property(property="type", type="string", enum={"regular", "followup", "emergency"}, example="regular")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Appointment created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/appointment/all",
 *     summary="List all Appointments",
 *     tags={"Appointments"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all appointments",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Appointment")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/appointment/get/{id}",
 *     summary="Get Appointment by Id",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment details",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/appointment/byPatient/{id}",
 *     summary="Get Appointment by Patient Id",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of patient's appointments",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Appointment")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/appointment/filter",
 *     summary="Filter Appointments",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         required=false,
 *         description="Filter by date (YYYY-MM-DD)",
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         required=false,
 *         description="Filter by status",
 *         @OA\Schema(type="string", enum={"scheduled", "completed", "cancelled", "rescheduled"})
 *     ),
 *     @OA\Parameter(
 *         name="doctor_id",
 *         in="query",
 *         required=false,
 *         description="Filter by doctor ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Filtered list of appointments",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Appointment")
 *         )
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/appointment/update/{id}",
 *     summary="Update Appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="appointment_date", type="string", format="date", example="2024-03-20"),
 *             @OA\Property(property="time_slot", type="string", example="10:00 AM"),
 *             @OA\Property(property="notes", type="string", example="Regular checkup"),
 *             @OA\Property(property="type", type="string", enum={"regular", "followup", "emergency"}, example="regular")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/appointment/status/{id}",
 *     summary="Update Appointment Status",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", enum={"scheduled", "completed", "cancelled", "rescheduled"}, example="completed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment status updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/appointment/appoint/{id}/reschedule",
 *     summary="Reschedule Appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="new_date", type="string", format="date", example="2024-03-25"),
 *             @OA\Property(property="new_time_slot", type="string", example="02:00 PM"),
 *             @OA\Property(property="reason", type="string", example="Doctor unavailable")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment rescheduled successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/appointment/appoint/{id}/reply",
 *     summary="Reply to the Reschedule Appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="response", type="string", enum={"accept", "reject"}, example="accept"),
 *             @OA\Property(property="message", type="string", example="New schedule works for me")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reply sent successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Appointment")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/appointment/delete/{id}",
 *     summary="Delete an Appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Appointment ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Appointment deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Appointment deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Appointment not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = [];

        if ($user->isDoctor()) {
            $appointments = $user->doctor->appointments()
                ->with(['patient.user', 'prescription'])
                ->latest()
                ->get();
        } elseif ($user->isPatient()) {
            $appointments = $user->patient->appointments()
                ->with(['doctor.user', 'prescription'])
                ->latest()
                ->get();
        } else {
            $appointments = Appointment::with(['doctor.user', 'patient.user', 'prescription'])
                ->latest()
                ->get();
        }

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'reason' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        if (!$user->isPatient()) {
            return response()->json(['message' => 'Only patients can book appointments'], 403);
        }

        $doctor = Doctor::findOrFail($request->doctor_id);
        if (!$doctor->is_available) {
            return response()->json(['message' => 'Doctor is not available for appointments'], 422);
        }

        // Check if the time slot is available
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingAppointment) {
            return response()->json(['message' => 'This time slot is already booked'], 422);
        }

        $appointment = $user->patient->appointments()->create([
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'status' => 'scheduled'
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully',
            'appointment' => $appointment->load(['doctor.user', 'patient.user'])
        ], 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load(['doctor.user', 'patient.user', 'prescription']));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_date' => ['sometimes', 'date', 'after:now'],
            'reason' => ['sometimes', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        if ($user->isPatient() && $appointment->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->isDoctor() && $appointment->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update($request->only(['appointment_date', 'reason', 'notes']));

        return response()->json([
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment->load(['doctor.user', 'patient.user'])
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        if ($user->isPatient() && $appointment->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->isDoctor() && $appointment->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->delete();
        return response()->json(['message' => 'Appointment cancelled successfully']);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:scheduled,completed,cancelled,no_show']
        ]);

        $user = Auth::user();
        if ($user->isDoctor() && $appointment->doctor_id !== $user->doctor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Appointment status updated successfully',
            'appointment' => $appointment->load(['doctor.user', 'patient.user'])
        ]);
    }

    public function create(Request $request)
    {
        // Implementation
    }

    public function all()
    {
        // Implementation
    }

    public function get($id)
    {
        // Implementation
    }

    public function byPatient($id)
    {
        // Implementation
    }

    public function filter(Request $request)
    {
        // Implementation
    }

    public function reschedule(Request $request, $id)
    {
        // Implementation
    }

    public function reply(Request $request, $id)
    {
        // Implementation
    }

    public function delete($id)
    {
        // Implementation
    }
}
