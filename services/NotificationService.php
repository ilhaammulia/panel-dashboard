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
    return Notification::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->get();
  }
}
