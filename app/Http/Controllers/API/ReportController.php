<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/report/create",
 *     summary="Create a new medical report",
 *     tags={"Reports"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"patient_id", "doctor_id", "title", "content"},
 *             @OA\Property(property="patient_id", type="integer", example=1),
 *             @OA\Property(property="doctor_id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Medical Examination Report"),
 *             @OA\Property(property="content", type="string", example="Patient shows signs of improvement..."),
 *             @OA\Property(property="diagnosis", type="string", example="Common cold with mild fever"),
 *             @OA\Property(property="recommendations", type="string", example="Rest and take prescribed medications"),
 *             @OA\Property(property="attachments", type="array", @OA\Items(type="string"), example=["xray.jpg", "blood_test.pdf"])
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Report created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Report")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/report/all",
 *     summary="List all reports",
 *     tags={"Reports"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all reports",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Report")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/report/{id}",
 *     summary="Get report by ID",
 *     tags={"Reports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Report ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Report details retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Report")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Report not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/report/patient/{patientId}",
 *     summary="Get reports by patient ID",
 *     tags={"Reports"},
 *     @OA\Parameter(
 *         name="patientId",
 *         in="path",
 *         required=true,
 *         description="Patient ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of patient reports",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Report")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/report/doctor/{doctorId}",
 *     summary="Get reports by doctor ID",
 *     tags={"Reports"},
 *     @OA\Parameter(
 *         name="doctorId",
 *         in="path",
 *         required=true,
 *         description="Doctor ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of doctor's reports",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Report")
 *         )
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/report/update/{id}",
 *     summary="Update a report",
 *     tags={"Reports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Report ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated Medical Report"),
 *             @OA\Property(property="content", type="string", example="Updated patient condition..."),
 *             @OA\Property(property="diagnosis", type="string", example="Updated diagnosis"),
 *             @OA\Property(property="recommendations", type="string", example="Updated recommendations"),
 *             @OA\Property(property="attachments", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Report updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Report")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Report not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/report/delete/{id}",
 *     summary="Delete a report",
 *     tags={"Reports"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Report ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Report deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Report deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Report not found",
 *         @OA\JsonContent(ref="#/components/schemas/Error")
 *     )
 * )
 */
class ReportController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'diagnosis' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'attachments' => 'nullable|array'
        ]);

        $report = Report::create($request->all());
        return response()->json($report, 201);
    }

    public function all()
    {
        $reports = Report::with(['patient.user', 'doctor.user'])->get();
        return response()->json($reports);
    }

    public function show($id)
    {
        $report = Report::with(['patient.user', 'doctor.user'])->findOrFail($id);
        return response()->json($report);
    }

    public function getByPatient($patientId)
    {
        $reports = Report::with(['doctor.user'])
            ->where('patient_id', $patientId)
            ->get();
        return response()->json($reports);
    }

    public function getByDoctor($doctorId)
    {
        $reports = Report::with(['patient.user'])
            ->where('doctor_id', $doctorId)
            ->get();
        return response()->json($reports);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'diagnosis' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'attachments' => 'nullable|array'
        ]);

        $report = Report::findOrFail($id);
        $report->update($request->all());

        return response()->json($report->fresh(['patient.user', 'doctor.user']));
    }

    public function delete($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }
} 