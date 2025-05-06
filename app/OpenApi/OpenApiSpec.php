<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="CareConnect API Documentation",
 *     description="API documentation for CareConnect Healthcare Management System",
 *     @OA\Contact(
 *         email="support@careconnect.com"
 *     )
 * )
 * 
 * @OA\PathItem(
 *     path="/api"
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Patient",
 *     description="Patient management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Doctor",
 *     description="Doctor management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for appointment management"
 * )
 * 
 * @OA\Tag(
 *     name="Prescriptions",
 *     description="API Endpoints for prescription management"
 * )
 * 
 * @OA\Tag(
 *     name="Reports",
 *     description="API Endpoints for medical reports"
 * )
 * 
 * @OA\Tag(
 *     name="Feedbacks",
 *     description="API Endpoints for feedback management"
 * )
 * 
 * @OA\Tag(
 *     name="Chats",
 *     description="API Endpoints for chat management"
 * )
 * 
 * @OA\Tag(
 *     name="Messages",
 *     description="API Endpoints for message management"
 * )
 * 
 * @OA\Tag(
 *     name="Sports",
 *     description="API Endpoints for sport recommendations"
 * )
 * 
 * @OA\Tag(
 *     name="Foods",
 *     description="API Endpoints for food recommendations"
 * )
 * 
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(property="errors", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="Success",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Success message"),
 *     @OA\Property(property="data", type="object")
 * )
 */
class OpenApiSpec
{
    /**
     * @OA\Get(
     *     path="/api/documentation",
     *     summary="Get API documentation",
     *     tags={"Documentation"},
     *     @OA\Response(
     *         response=200,
     *         description="API documentation"
     *     )
     * )
     */
    public function index()
    {
        // This method is just for documentation purposes
    }

    /**
     * @OA\Get(
     *     path="/api",
     *     summary="API Root",
     *     @OA\Response(
     *         response=200,
     *         description="API is running"
     *     )
     * )
     */
    public function root()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/doctors",
     *     summary="Get all doctors",
     *     tags={"Doctors"},
     *     @OA\Response(
     *         response=200,
     *         description="List of doctors",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Doctor"))
     *     )
     * )
     */
    public function getDoctors()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/doctors",
     *     summary="Create a new doctor",
     *     tags={"Doctors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Doctor")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Doctor created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Doctor")
     *     )
     * )
     */
    public function createDoctor()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}",
     *     summary="Get doctor by ID",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor details",
     *         @OA\JsonContent(ref="#/components/schemas/Doctor")
     *     )
     * )
     */
    public function getDoctor()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/doctors/{id}",
     *     summary="Update doctor",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Doctor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Doctor")
     *     )
     * )
     */
    public function updateDoctor()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/doctors/{id}",
     *     summary="Delete doctor",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor deleted successfully"
     *     )
     * )
     */
    public function deleteDoctor()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}/appointments",
     *     summary="Get doctor's appointments",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of doctor's appointments",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Appointment"))
     *     )
     * )
     */
    public function getDoctorAppointments()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}/prescriptions",
     *     summary="Get doctor's prescriptions",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of doctor's prescriptions",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prescription"))
     *     )
     * )
     */
    public function getDoctorPrescriptions()
    {
    }

    // Patient endpoints
    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Get all patients",
     *     tags={"Patients"},
     *     @OA\Response(
     *         response=200,
     *         description="List of patients",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Patient"))
     *     )
     * )
     */
    public function getPatients()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Create a new patient",
     *     tags={"Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Patient")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Patient created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Patient")
     *     )
     * )
     */
    public function createPatient()
    {
    }

    // Appointment endpoints
    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="Create a new appointment",
     *     tags={"Appointments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Appointment")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Appointment")
     *     )
     * )
     */
    public function createAppointment()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/appointments/{id}/status",
     *     summary="Update appointment status",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"scheduled", "completed", "cancelled"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment status updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Appointment")
     *     )
     * )
     */
    public function updateAppointmentStatus()
    {
    }

    // Prescription endpoints
    /**
     * @OA\Post(
     *     path="/api/prescriptions",
     *     summary="Create a new prescription",
     *     tags={"Prescriptions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Prescription")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Prescription created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Prescription")
     *     )
     * )
     */
    public function createPrescription()
    {
    }

    // Message endpoints
    /**
     * @OA\Post(
     *     path="/api/messages",
     *     summary="Send a new message",
     *     tags={"Messages"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Message sent successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     )
     * )
     */
    public function sendMessage()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/messages/conversation/{user_id}",
     *     summary="Get conversation with a user",
     *     tags={"Messages"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of messages in the conversation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Message"))
     *     )
     * )
     */
    public function getConversation()
    {
    }
} 