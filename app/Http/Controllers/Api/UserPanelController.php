<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Services\UserPanelService;
use Services\AntibotService;
use Illuminate\Support\Carbon;

class UserPanelController extends Controller
{
    protected $userPanelService;

    public function __construct(UserPanelService $userPanelService)
    {
        $this->userPanelService = $userPanelService;
    }

    public function update(Request $request)
    {
        $userId = $request->user_id;
        $domain = $request->domain;
        if (!$userId || !$domain) return;

        $panel = $this->userPanelService->getByDomain($domain);
        if ($panel->user_id !== $userId) return;
        $data = $request->only(['user_id', 'panel_id', 'name', 'status', 'domain', 'telegram_bot_token', 'telegram_chat_id', 'expired_at']);

        return $this->userPanelService->update($panel->id, $data);
    }

    public function stats(Request $request)
    {
        $antibot = new AntibotService($request->ip(), $request->header('User-Agent'));
        if (env('ANTIBOT', false) && !$antibot->verify()) {
            return response()->json(['status' => 'blocked.'], 404);
        }

        $panel = $this->userPanelService->getByDomain($request->query('domain'));
        if (!$panel) {
            return response()->json(['status' => 'not found.'], 404);
        }

        if ($panel->status !== 'active' || $panel->Panel->status !== 'active') {
            return response()->json(['status' => 'not active'], 404);
        }

        return response()->json([
            'id' => $panel->id,
            'domain' => $panel->domain,
            'status' => $panel->status,
        ], 200);
    }

    public function data(Request $request)
    {
        $userId = $request->query('user_id');
        $domain = $request->query('domain');
        if (!$userId || !$domain) return;

        $panel = $this->userPanelService->getByDomain($domain);
        if ($panel->user_id !== $userId) return;

        $metadata = $this->userPanelService->getMetadataByDomain($domain);

        return response()->json($metadata, 200);
    }
}
