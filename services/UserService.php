<?php

namespace Services;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Services\NotificationService;

class UserService
{
  protected $notificationService;

  public function __construct()
  {
    $this->notificationService = new NotificationService();
  }

  public function register($input)
  {
    $input['username'] = strtolower($input['username']);
    $input['email'] = strtolower($input['email']);

    $newUser = new CreateNewUser();
    $newUser->create($input);
    $name = $input['name'];
    $this->notificationService->create("$name has been arrived at the server.", 'pi pi-user', 'green');

    return $newUser;
  }

  public function login($username, $password)
  {

    if (Auth::attempt(['username' => $username, 'password' => $password])) {
      $user = Auth::user();
      return $user;
    }

    return null;
  }

  public function getUser($id)
  {
    $user = User::find($id);
    return $user;
  }

  public function update($id, $input)
  {
    $user = User::find($id);
    $is_exists = User::where('id', '!=', $id)->where(function ($q) use ($input) {
      $q->where('username', $input['username'])->orWhere('email', $input['email']);
    })->count();

    $input['username'] = strtolower($input['username']);
    $input['email'] = strtolower($input['email']);
    if (array_key_exists('password', $input)) {
      $input['password'] = Hash::make($input['password']);
    }

    if ($user && !$is_exists) {
      $user->update($input);
    }

    return $user;
  }

  public function delete($id)
  {
    $user = User::find($id);
    if ($user) {
      $user->delete();
    }
    return $user;
  }

  public function delete_many($ids)
  {
    $users = User::whereIn('id', $ids)->delete();
    $this->notificationService->create("Some users has been removed from the server.", 'pi pi-trash', 'red');
    return $users;
  }

  public function json($data)
  {
    return $data->toArray();
  }
}
