<?php

namespace Services;

use App\Models\Notification;
use Illuminate\Support\Carbon;

class NotificationService
{
  public function create($message, $icon = 'pi pi-bell', $color = 'yellow')
  {
    Notification::create([
      'message' => $message,
      'icon' => $icon,
      'color' => $color
    ]);
  }

  public function today()
  {
    return Notification::orderBy('created_at', 'desc')->limit(15)->get();
  }
}
