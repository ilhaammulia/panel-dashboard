<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Services\UserService;
use Services\UserPanelService;

class AuthController extends Controller
{
    protected $userService;
    protected $userPanelService;

    public function __construct(UserService $userService, UserPanelService $userPanelService)
    {
        $this->userService = $userService;
        $this->userPanelService = $userPanelService;
    }

    public function login(Request $request)
    {
        try {
            $form = $request->only(['username', 'password']);

            $user = $this->userService->login($form['username'], $form['password']);
            $panel = $this->userPanelService->checkPanelIfExists($user?->id, $request->domain);
            if ($user && $panel) {
                return response()->json([...$this->userService->json($user)], 200);
            }
            return response()->json(['errors' => 'User in this domain not found.'], 404);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
}
