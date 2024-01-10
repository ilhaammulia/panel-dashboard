<?php

namespace Services;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Error;

class UserService
{
  public function register($input)
  {
    try {
      $newUser = new CreateNewUser();
      $newUser->create($input);

      return $newUser;
    } catch (Error $err) {
      throw new Error($err);
    }
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
  }

  public function update($id, $input)
  {
  }
}
