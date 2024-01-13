<?php

namespace Services;

use App\Models\User;
use App\Models\UserPanel;

class UserPanelService
{
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
}
