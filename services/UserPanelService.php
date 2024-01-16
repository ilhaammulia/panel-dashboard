<?php

namespace Services;

use App\Models\UserPanel;
use Carbon\Carbon;
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

  public function update($id, $input)
  {
    $input['expired_at'] = Carbon::parse($input['expired_at']);
    $panel = UserPanel::find($id);
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
    $panels = UserPanel::whereIn('id', $ids);
    $panels->delete();
    $this->notificationService->create("Some user panels has been removed from the server.", 'pi pi-sitemap', 'red');
    return $panels;
  }
}
