<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->appointment->status);
        $date = $this->appointment->appointment_date->format('F j, Y g:i A');

        return (new MailMessage)
            ->subject("Appointment {$status}")
            ->line("Your appointment has been {$this->appointment->status}.")
            ->line("Date: {$date}")
            ->line("Doctor: {$this->appointment->doctor->user->name}")
            ->line("Patient: {$this->appointment->patient->user->name}")
            ->line("Reason: {$this->appointment->reason}")
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Thank you for using CareConnect!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'date' => $this->appointment->appointment_date,
            'doctor_name' => $this->appointment->doctor->user->name,
            'patient_name' => $this->appointment->patient->user->name,
        ];
    }
} 