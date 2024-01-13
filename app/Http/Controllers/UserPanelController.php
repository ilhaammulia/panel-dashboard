<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPanel;
use Services\UserPanelService;

class UserPanelController extends Controller
{
    protected $userPanelService;

    public function __construct(UserPanelService $userPanelService)
    {
        $this->userPanelService = $userPanelService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panels = UserPanel::all();
        return $panels;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->only(['user_id', 'panel_id', 'name', 'domain', 'telegram_bot_token', 'telegram_chat_id']);
            $panel = $this->userPanelService->create($data);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $panel = $this->userPanelService->get($id);
        return $panel;
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
            $panel = $this->userPanelService->update($id, $request);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $panel = $this->userPanelService->delete($id);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
