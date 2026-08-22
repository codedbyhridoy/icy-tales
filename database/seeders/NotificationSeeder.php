<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $doctor = Doctor::first();
        $patient = Patient::first();

        if ($doctor && $doctor->user_id) {
            Notification::create([
                'user_id' => $doctor->user_id,
                'type' => 'push',
                'title' => 'New Appointment',
                'body' => 'You have a new appointment scheduled tomorrow.',
                'template' => 'appointment_alert',
                'status' => 0,
                'notifiable_id' => 12,
                'notifiable_type' => Appointment::class,
            ]);
        }

        if ($patient && $patient->user_id) {
            Notification::create([
                'user_id' => $patient->user_id,
                'type' => 'push',
                'title' => 'Lab Results Ready',
                'body' => 'Your lab results are now available in your profile.',
                'template' => 'lab_results',
                'status' => 0,
                'notifiable_id' => 12,
                'notifiable_type' => Appointment::class,
            ]);
        }
    }
}
