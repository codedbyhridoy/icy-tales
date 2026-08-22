<?php

namespace App\Traits;

use App\Models\Notification;

trait NotificationTrait
{
    public function setNotification($data)
    {
        $notification = Notification::create($data);

        return $notification;
    }
}
