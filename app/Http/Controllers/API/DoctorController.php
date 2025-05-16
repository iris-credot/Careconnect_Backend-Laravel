<?php

namespace App\Http\Controllers\API;

use App\Models\Doctor;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;

class DoctorController extends Controller
{
    // Get all doctors
    public function getAllDoctors()
    {
        $doctors = Doctor::with('user')->get();
        return response()->json(['doctors' => $doctors], 200);
    }

    // Get doctor by ID
    public function getDoctorById($id)
    {
        $doctor = Doctor::with(['user', 'appointments'])->find($id);
        if (!$doctor) {
            throw new NotFoundException('Doctor not found');
        }

        return response()->json(['doctor' => $doctor], 200);
    }

    // Create new doctor profile
    public function createDoctor(Request $request)
    {
        $request->validate([
            'user' => 'required|string|exists:users,_id',
            'specialization' => 'required|string',
            'license_number' => 'required|string',
            'experience_years' => 'nullable|integer',
            'education' => 'nullable|string',
            'hospital_affiliation' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'consultation_fee' => 'nullable|numeric',
        ]);

        $user = User::find($request->user);
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $existingDoctor = Doctor::where('user_id', $user->_id)->first();
        if ($existingDoctor) {
            throw new BadRequestException('Doctor profile already exists for this user');
        }

        if ($user->role !== 'doctor') {
            $user->role = 'doctor';
            $user->save();
        }

        $doctor = Doctor::create([
            'user_id' => $user->_id,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'experience_years' => $request->experience_years,
            'education' => $request->education,
            'hospital_affiliation' => $request->hospital_affiliation,
            'is_available' => $request->is_available,
            'consultation_fee' => $request->consultation_fee,
        ]);

        return response()->json(['message' => 'Doctor created successfully', 'doctor' => $doctor], 201);
    }

    // Update doctor
    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            throw new NotFoundException('Doctor not found');
        }

        $doctor->update($request->all());

        return response()->json(['message' => 'Doctor updated successfully', 'doctor' => $doctor->fresh('user')], 200);
    }

    // Delete doctor
    public function deleteDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            throw new NotFoundException('Doctor not found');
        }

        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }

    // Get patients for doctor
    public function getDoctorPatients($doctorId)
    {
        $doctor = Doctor::with(['appointments.user'])->find($doctorId); // Assuming appointments have user

        if (!$doctor) {
            throw new NotFoundException('Doctor not found');
        }

        // Collect unique patients
        $patients = $doctor->appointments->map(function ($appointment) {
            return $appointment->patient ?? null;
        })->filter()->unique('id')->values();

        return response()->json(['patients' => $patients], 200);
    }
}
