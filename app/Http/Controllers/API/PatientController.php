<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Post(
 *     path="/api/patient/create",
 *     summary="Create a Patient Account",
 *     tags={"Patient"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "date_of_birth", "gender"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *             @OA\Property(property="blood_group", type="string", example="O+")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Patient account created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Patient")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/patient/all",
 *     summary="List all Patient Accounts",
 *     tags={"Patient"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all patients",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Patient")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/patient/getPatient/{id}",
 *     summary="Get Patient Account by Id",
 *     tags={"Patient"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient details",
 *         @OA\JsonContent(ref="#/components/schemas/Patient")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Patient not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/patient/getPatientByUser/{id}",
 *     summary="Get Patient Account by User Id",
 *     tags={"Patient"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient details",
 *         @OA\JsonContent(ref="#/components/schemas/Patient")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Patient not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/patient/profile/{id}",
 *     summary="Update Profile of patient",
 *     tags={"Patient"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *             @OA\Property(property="blood_group", type="string", example="O+")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient profile updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Patient")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Patient not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/patient/delete/{id}",
 *     summary="Delete a Patient Account",
 *     tags={"Patient"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Patient ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Patient deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Patient deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Patient not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $patient = $user->patient()->create([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_history' => $request->medical_history,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient->load('user')
        ], 201);
    }

    public function show(Patient $patient)
    {
        return response()->json($patient->load('user'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'blood_group' => ['sometimes', 'nullable', 'string', 'max:5'],
            'allergies' => ['sometimes', 'nullable', 'string'],
            'medical_history' => ['sometimes', 'nullable', 'string'],
            'emergency_contact_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        $patient->user->update($request->only(['name', 'phone', 'address']));
        $patient->update($request->only([
            'blood_group',
            'allergies',
            'medical_history',
            'emergency_contact_name',
            'emergency_contact_phone'
        ]));

        return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient->load('user')
        ]);
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete();
        return response()->json(['message' => 'Patient deleted successfully']);
    }

    public function appointments(Patient $patient)
    {
        $appointments = $patient->appointments()
            ->with(['doctor.user', 'prescription'])
            ->latest()
            ->get();

        return response()->json($appointments);
    }

    public function prescriptions(Patient $patient)
    {
        $prescriptions = $patient->prescriptions()
            ->with(['doctor.user', 'appointment'])
            ->latest()
            ->get();

        return response()->json($prescriptions);
    }

    public function reports(Patient $patient)
    {
        $reports = $patient->reports()
            ->with(['doctor.user'])
            ->latest()
            ->get();

        return response()->json($reports);
    }

    public function feedbacks(Patient $patient)
    {
        $feedbacks = $patient->feedbacks()
            ->latest()
            ->get();

        return response()->json($feedbacks);
    }

    public function create(Request $request)
    {
        // Implementation
    }

    public function all()
    {
        // Implementation
    }

    public function getPatient($id)
    {
        // Implementation
    }

    public function getPatientByUser($id)
    {
        // Implementation
    }

    public function updateProfile(Request $request, $id)
    {
        // Implementation
    }

    public function delete($id)
    {
        // Implementation
    }
}
