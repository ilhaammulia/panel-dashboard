<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Services\UserPanelService;

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
}
