<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\NotificationController;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // Create a new appointment
    public function createAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'date' => 'required|date',
            'timeSlot' => 'required|string',
            'reason' => 'nullable|string',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Missing or invalid required fields');
        }

        $appointment = Appointment::create($request->only([
            'patient_id', 'doctor_id', 'date', 'timeSlot', 'reason', 'status', 'notes'
        ]));

        // Send notifications to patient and doctor
        $this->notificationController->sendNotification([
            'user' => $appointment->patient_id,
            'message' => 'You have a new appointment scheduled.',
            'type' => 'appointment',
        ]);

        $this->notificationController->sendNotification([
            'user' => $appointment->doctor_id,
            'message' => 'A new appointment has been booked with you.',
            'type' => 'appointment',
        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment,
        ], Response::HTTP_CREATED);
    }

    // Get all appointments
    public function getAllAppointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        return response()->json(['appointments' => $appointments], 200);
    }

    // Get appointment by ID
    public function getAppointmentById($id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->find($id);

        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        return response()->json(['appointment' => $appointment], 200);
    }

    // Update appointment
    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        $appointment->fill($request->all());
        $appointment->save();

        // Notify patient and doctor about update
        $this->notificationController->sendNotification([
            'user' => $appointment->patient_id,
            'message' => 'Your appointment details have been updated.',
            'type' => 'appointment',
        ]);
        $this->notificationController->sendNotification([
            'user' => $appointment->doctor_id,
            'message' => 'An appointment assigned to you has been updated.',
            'type' => 'appointment',
        ]);

        return response()->json([
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment,
        ], 200);
    }

    // Delete appointment
    public function deleteAppointment($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }

    // Filter appointments by doctor, patient, status or date
    public function filterAppointments(Request $request)
    {
        $query = Appointment::query();

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $appointments = $query->with(['patient', 'doctor'])->get();

        return response()->json(['appointments' => $appointments], 200);
    }

    // Change appointment status
    public function changeAppointmentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Confirmed,Completed,Cancelled,Rescheduled,Denied',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Invalid status value.');
        }

        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'appointment' => $appointment,
        ], 200);
    }

    // Get appointments by patient ID
    public function getAppointmentsByPatientId($patientId)
    {
        $appointments = Appointment::with(['patient', 'doctor'])->where('patient_id', $patientId)->get();

        if ($appointments->isEmpty()) {
            throw new NotFoundException("No appointments found for patient ID $patientId");
        }

        return response()->json(['appointments' => $appointments], 200);
    }

    // Reschedule appointment
    public function rescheduleAppointment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'newDate' => 'required|date',
            'newTimeSlot' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Missing required fields: newDate or newTimeSlot.');
        }

        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        $appointment->newDate = $request->newDate;
        $appointment->newTimeSlot = $request->newTimeSlot;
        $appointment->status = 'Rescheduled';
        $appointment->save();

        $this->notificationController->sendNotification([
            'user' => $appointment->patient_id,
            'message' => 'Your appointment reschedule request is pending confirmation.',
            'type' => 'appointment',
        ]);
        $this->notificationController->sendNotification([
            'user' => $appointment->doctor_id,
            'message' => 'A patient has requested to reschedule an appointment.',
            'type' => 'appointment',
        ]);

        return response()->json([
            'message' => 'Appointment rescheduled successfully',
            'appointment' => $appointment,
        ], 200);
    }

    // Respond to reschedule request (accept or deny)
    public function respondToRescheduleRequest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:accept,deny',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Invalid action. Must be "accept" or "deny".');
        }

        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new NotFoundException("No appointment found with ID $id");
        }

        if (!$appointment->newDate || !$appointment->newTimeSlot) {
            throw new BadRequestException('No reschedule request found for this appointment.');
        }

        if ($request->action === 'accept') {
            $appointment->date = $appointment->newDate;
            $appointment->timeSlot = $appointment->newTimeSlot;
            $appointment->status = 'Confirmed';
            $appointment->newDate = null;
            $appointment->newTimeSlot = null;
            $appointment->save();

            $this->notificationController->sendNotification([
                'user' => $appointment->patient_id,
                'message' => "Your reschedule request has been accepted. New appointment: {$appointment->date} at {$appointment->timeSlot}",
                'type' => 'appointment',
            ]);
            $this->notificationController->sendNotification([
                'user' => $appointment->doctor_id,
                'message' => 'You accepted a reschedule request.',
                'type' => 'appointment',
            ]);

            return response()->json(['message' => 'Reschedule accepted.', 'appointment' => $appointment], 200);
        }

        if ($request->action === 'deny') {
            $appointment->status = 'Denied';
            $appointment->newDate = null;
            $appointment->newTimeSlot = null;
            $appointment->save();

            $this->notificationController->sendNotification([
                'user' => $appointment->patient_id,
                'message' => 'Your reschedule request has been denied.',
                'type' => 'appointment',
            ]);
            $this->notificationController->sendNotification([
                'user' => $appointment->doctor_id,
                'message' => 'You denied a reschedule request.',
                'type' => 'appointment',
            ]);

            return response()->json(['message' => 'Reschedule denied.', 'appointment' => $appointment], 200);
        }
    }
}
