<?php

namespace Services;

use App\Models\Panel;
use Illuminate\Support\Facades\Storage;
use Services\NotificationService;

class PanelService
{
  protected $notificationService;

  public function __construct()
  {
    $this->notificationService = new NotificationService();
  }

  public function create($input)
  {
    $panel = Panel::create($input);
    $name = $panel->name;
    $this->notificationService->create("Panel $name has been added to the server.", "pi pi-desktop", "blue");

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
      $name = $panel->name;
      $this->notificationService->create("Panel $name has been updated.", "pi pi-desktop", "blue");
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

  public function delete_many($ids)
  {
    $panels = Panel::whereIn('id', $ids);
    $panels->get()->map(function ($panel) {
      if ($panel->icon) {
        $parsedUrl = parse_url($panel->icon);
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        Storage::delete(str_replace('/storage/', '', $path));
      }
    });
    $panels->delete();
    $this->notificationService->create("Some panels has been removed from the server.", 'pi pi-trash', 'red');
    return $panels;
  }
}
