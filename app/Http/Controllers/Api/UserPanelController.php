<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Services\UserPanelService;
use Services\AntibotService;

class UserPanelController extends Controller
{
    protected $userPanelService;

    public function __construct(UserPanelService $userPanelService)
    {
        $this->userPanelService = $userPanelService;
    }

    public function visitors(Request $request)
    {
    }

    public function stats(Request $request)
    {
        $antibot = new AntibotService($request->ip(), $request->header('User-Agent'));

        if (!$antibot->verify()) {
            return response()->json(['status' => 'blocked.'], 404);
        }

        $panel = $this->userPanelService->getByDomain($request->query('domain'));
        if (!$panel) {
            return response()->json(['status' => 'not found.'], 404);
        }

        if ($panel->status !== 'active') {
            return response()->json(['status' => 'not active'], 403);
        }

        return response()->json([
            'id' => $panel->id,
            'domain' => $panel->domain,
            'status' => $panel->status,
        ], 200);
    }
}
