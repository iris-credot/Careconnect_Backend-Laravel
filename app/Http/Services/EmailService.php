<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Exception;

class EmailService
{
    public static function sendEmail(string $to, string $subject, string $body)
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($to);
                $message->subject($subject);
            });

            \Log::info('Email sent successfully to ' . $to);
            return true;

        } catch (Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
            throw new Exception('Failed to send email');
        }
    }
}
