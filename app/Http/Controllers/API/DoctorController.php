<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Post(
 *     path="/api/doctor/create",
 *     summary="Create a new doctor account",
 *     tags={"Doctors"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "specialization", "license_number"},
 *             @OA\Property(property="name", type="string", example="Dr. John Smith"),
 *             @OA\Property(property="email", type="string", format="email", example="john.smith@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="phone", type="string", example="+1234567890"),
 *             @OA\Property(property="address", type="string", example="123 Medical Center Dr"),
 *             @OA\Property(property="specialization", type="string", example="Cardiology"),
 *             @OA\Property(property="license_number", type="string", example="MD123456"),
 *             @OA\Property(property="experience_years", type="integer", example=10),
 *             @OA\Property(property="education", type="string", example="MD, Harvard Medical School"),
 *             @OA\Property(property="hospital_affiliation", type="string", example="General Hospital")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Doctor created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Doctor")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/doctor/all",
 *     summary="List all doctors",
 *     tags={"Doctors"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all doctors",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Doctor")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/doctor/{id}",
 *     summary="Get doctor details by ID",
 *     tags={"Doctors"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Doctor ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Doctor details retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Doctor")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Doctor not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/doctor/update/{id}",
 *     summary="Update doctor details",
 *     tags={"Doctors"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Doctor ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Dr. John Smith"),
 *             @OA\Property(property="phone", type="string", example="+1234567890"),
 *             @OA\Property(property="address", type="string", example="123 Medical Center Dr"),
 *             @OA\Property(property="specialization", type="string", example="Cardiology"),
 *             @OA\Property(property="license_number", type="string", example="MD123456"),
 *             @OA\Property(property="experience_years", type="integer", example=10),
 *             @OA\Property(property="education", type="string", example="MD, Harvard Medical School"),
 *             @OA\Property(property="hospital_affiliation", type="string", example="General Hospital")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Doctor updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Doctor")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Doctor not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/doctor/delete/{id}",
 *     summary="Delete a doctor account",
 *     tags={"Doctors"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Doctor ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Doctor deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Doctor deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Doctor not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class DoctorController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:50|unique:doctors',
            'experience_years' => 'nullable|integer|min:0',
            'education' => 'nullable|string|max:255',
            'hospital_affiliation' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'doctor',
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'experience_years' => $request->experience_years,
                'education' => $request->education,
                'hospital_affiliation' => $request->hospital_affiliation
            ]);

            DB::commit();
            return response()->json($doctor, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating doctor account'], 500);
        }
    }

    public function all()
    {
        $doctors = Doctor::with('user')->get();
        return response()->json($doctors);
    }

    public function show($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        return response()->json($doctor);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'sometimes|string|max:255',
            'license_number' => 'sometimes|string|max:50|unique:doctors,license_number,' . $id,
            'experience_years' => 'nullable|integer|min:0',
            'education' => 'nullable|string|max:255',
            'hospital_affiliation' => 'nullable|string|max:255'
        ]);

        $doctor = Doctor::findOrFail($id);
        
        DB::beginTransaction();
        try {
            if ($request->has('name') || $request->has('phone') || $request->has('address')) {
                $doctor->user->update($request->only(['name', 'phone', 'address']));
            }

            $doctor->update($request->only([
                'specialization',
                'license_number',
                'experience_years',
                'education',
                'hospital_affiliation'
            ]));

            DB::commit();
            return response()->json($doctor->fresh(['user']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating doctor account'], 500);
        }
    }

    public function delete($id)
    {
        $doctor = Doctor::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $doctor->user->delete();
            $doctor->delete();
            
            DB::commit();
            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting doctor account'], 500);
        }
    }

    public function appointments(Doctor $doctor)
    {
        $appointments = $doctor->appointments()
            ->with(['patient.user', 'prescription'])
            ->latest()
            ->get();

        return response()->json($appointments);
    }

    public function prescriptions(Doctor $doctor)
    {
        $prescriptions = $doctor->prescriptions()
            ->with(['patient.user', 'appointment'])
            ->latest()
            ->get();

        return response()->json($prescriptions);
    }
}
