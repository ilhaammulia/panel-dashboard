<?php

namespace Services;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
  public function register($input)
  {
    $newUser = new CreateNewUser();
    $newUser->create($input);

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
    if ($user) {
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

  public function json($data)
  {
    return $data->toArray();
  }
}
