<?php

namespace Services;

use App\Models\Panel;

class PanelService
{

  public function create($input)
  {
    $panel = Panel::create($input);
    return $panel;
  }

  public function get($id)
  {
    $panel = Panel::find($id);
    return $panel;
  }

  public function update($id, $input)
  {
    $panel = Panel::find($id);
    if ($panel) {
      $panel->update($input);
    }
    return $panel;
  }

  public function delete($id)
  {
    $panel = Panel::find($id);
    if ($panel) {
      $panel->delete();
    }
    return $panel;
  }
}
