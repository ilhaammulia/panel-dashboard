<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Inertia\Inertia;
use Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('updated_at', 'desc')->get()->map(function ($user) {
            return [
                ...$user->toArray(),
                'panels' => $user->UserPanels->toArray(),
            ];
        });
        return Inertia::render('Admin/Users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->only(['name', 'username', 'email', 'role_id', 'password', 'password_confirmation', 'telegram_bot_token', 'telegram_chat_id']);
            $this->userService->register($data);
            return redirect()->to(route('users.index'));
        } catch (\Exception $th) {
            return redirect()->to(route('users.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUser($id);
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = $this->userService->update($id, $request);
            return redirect()->to(route('users.index'));
        } catch (\Throwable $th) {
            return redirect()->to(route('users.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userService->delete($id);
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete_many(Request $request)
    {
        $this->userService->delete_many($request->ids);
        return redirect()->to(route('users.index'));
    }
}
