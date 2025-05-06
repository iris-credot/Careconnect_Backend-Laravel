<?php

namespace App\OpenApi;

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", enum={"patient", "doctor", "admin"}, example="patient"),
 *     @OA\Property(property="profile_photo", type="string", example="profile.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Patient",
 *     required={"user_id", "date_of_birth", "gender"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *     @OA\Property(property="blood_group", type="string", example="O+"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Doctor",
 *     required={"user_id", "specialization", "license_number"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="specialization", type="string", example="Cardiology"),
 *     @OA\Property(property="license_number", type="string", example="DOC123456"),
 *     @OA\Property(property="qualifications", type="string", example="MD, PhD"),
 *     @OA\Property(property="experience_years", type="integer", example=10),
 *     @OA\Property(property="consultation_fee", type="number", format="float", example=100.00),
 *     @OA\Property(property="is_available", type="boolean", example=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Appointment",
 *     required={"doctor_id", "patient_id", "appointment_date", "time_slot"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="doctor_id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="appointment_date", type="string", format="date", example="2024-03-20"),
 *     @OA\Property(property="time_slot", type="string", example="10:00 AM"),
 *     @OA\Property(property="status", type="string", enum={"scheduled", "completed", "cancelled", "rescheduled"}, example="scheduled"),
 *     @OA\Property(property="notes", type="string", example="Regular checkup"),
 *     @OA\Property(property="doctor", ref="#/components/schemas/Doctor"),
 *     @OA\Property(property="patient", ref="#/components/schemas/Patient"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Health",
 *     required={"patient_id", "height", "weight", "blood_pressure"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="height", type="number", format="float", example=175.5),
 *     @OA\Property(property="weight", type="number", format="float", example=70.5),
 *     @OA\Property(property="blood_pressure", type="string", example="120/80"),
 *     @OA\Property(property="allergies", type="string", example="Penicillin"),
 *     @OA\Property(property="medical_conditions", type="string", example="Hypertension"),
 *     @OA\Property(property="patient", ref="#/components/schemas/Patient"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Chat",
 *     required={"sender_id", "receiver_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="sender_id", type="integer", example=1),
 *     @OA\Property(property="receiver_id", type="integer", example=2),
 *     @OA\Property(property="sender", ref="#/components/schemas/User"),
 *     @OA\Property(property="receiver", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Message",
 *     required={"chat_id", "sender_id", "content"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="chat_id", type="integer", example=1),
 *     @OA\Property(property="sender_id", type="integer", example=1),
 *     @OA\Property(property="content", type="string", example="Hello, how are you?"),
 *     @OA\Property(property="is_read", type="boolean", example=false),
 *     @OA\Property(property="chat", ref="#/components/schemas/Chat"),
 *     @OA\Property(property="sender", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Feedback",
 *     required={"user_id", "rating", "comment"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="comment", type="string", example="Great service!"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="pending"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Report",
 *     required={"patient_id", "doctor_id", "diagnosis"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="patient_id", type="integer", example=1),
 *     @OA\Property(property="doctor_id", type="integer", example=1),
 *     @OA\Property(property="diagnosis", type="string", example="Common cold"),
 *     @OA\Property(property="prescription", type="string", example="Take rest and drink plenty of water"),
 *     @OA\Property(property="notes", type="string", example="Follow up in 1 week"),
 *     @OA\Property(property="patient", ref="#/components/schemas/Patient"),
 *     @OA\Property(property="doctor", ref="#/components/schemas/Doctor"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Notification",
 *     required={"user_id", "title", "message"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="New Appointment"),
 *     @OA\Property(property="message", type="string", example="Your appointment is scheduled for tomorrow"),
 *     @OA\Property(property="is_read", type="boolean", example=false),
 *     @OA\Property(property="type", type="string", enum={"appointment", "message", "system"}, example="appointment"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="SportRecommendation",
 *     required={"title", "description", "duration"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Morning Walk"),
 *     @OA\Property(property="description", type="string", example="30 minutes of brisk walking"),
 *     @OA\Property(property="duration", type="string", example="30 minutes"),
 *     @OA\Property(property="intensity", type="string", enum={"low", "medium", "high"}, example="medium"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="FoodRecommendation",
 *     required={"title", "description", "calories"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Healthy Breakfast"),
 *     @OA\Property(property="description", type="string", example="Oatmeal with fruits"),
 *     @OA\Property(property="calories", type="integer", example=300),
 *     @OA\Property(property="meal_type", type="string", enum={"breakfast", "lunch", "dinner", "snack"}, example="breakfast"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Schemas
{
    // This class is just for documentation purposes
} 