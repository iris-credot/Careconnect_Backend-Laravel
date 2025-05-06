<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected $patient;
    protected $doctor;
    protected $patientToken;
    protected $doctorToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create patient
        $patientUser = User::factory()->create(['role' => 'patient']);
        $this->patient = Patient::factory()->create(['user_id' => $patientUser->id]);
        $this->patientToken = $patientUser->createToken('test-token')->plainTextToken;

        // Create doctor
        $doctorUser = User::factory()->create(['role' => 'doctor']);
        $this->doctor = Doctor::factory()->create(['user_id' => $doctorUser->id]);
        $this->doctorToken = $doctorUser->createToken('test-token')->plainTextToken;
    }

    public function test_patient_can_book_appointment()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->patientToken)
            ->postJson('/api/appointments', [
                'doctor_id' => $this->doctor->id,
                'appointment_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'reason' => 'Regular checkup',
                'notes' => 'First visit'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'appointment' => [
                    'id',
                    'doctor_id',
                    'patient_id',
                    'appointment_date',
                    'status',
                    'reason',
                    'notes'
                ]
            ]);
    }

    public function test_doctor_can_update_appointment_status()
    {
        $appointment = $this->patient->appointments()->create([
            'doctor_id' => $this->doctor->id,
            'appointment_date' => now()->addDays(2),
            'reason' => 'Regular checkup',
            'status' => 'scheduled'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->doctorToken)
            ->putJson("/api/appointments/{$appointment->id}/status", [
                'status' => 'completed'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Appointment status updated successfully'
            ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed'
        ]);
    }

    public function test_patient_can_view_their_appointments()
    {
        $this->patient->appointments()->create([
            'doctor_id' => $this->doctor->id,
            'appointment_date' => now()->addDays(2),
            'reason' => 'Regular checkup',
            'status' => 'scheduled'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->patientToken)
            ->getJson('/api/appointments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'doctor_id',
                    'patient_id',
                    'appointment_date',
                    'status',
                    'reason',
                    'notes',
                    'doctor' => [
                        'user' => [
                            'name',
                            'email'
                        ]
                    ]
                ]
            ]);
    }

    public function test_doctor_can_view_their_appointments()
    {
        $this->patient->appointments()->create([
            'doctor_id' => $this->doctor->id,
            'appointment_date' => now()->addDays(2),
            'reason' => 'Regular checkup',
            'status' => 'scheduled'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->doctorToken)
            ->getJson('/api/appointments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'doctor_id',
                    'patient_id',
                    'appointment_date',
                    'status',
                    'reason',
                    'notes',
                    'patient' => [
                        'user' => [
                            'name',
                            'email'
                        ]
                    ]
                ]
            ]);
    }
} 