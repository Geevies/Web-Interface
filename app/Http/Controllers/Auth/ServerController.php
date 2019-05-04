<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\Server\ServerQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;


class ServerController extends Controller
{
    public function beginLogin(Request $request)
    {
        $client_token = $request->input('token', '');
        // Check if out token is MD5 hash, what it should be
        if(!preg_match('/^[a-f0-9]{32}$/', $client_token)) {
            abort(400, 'Invalid request.');
        }
        // Let's store our token for later use
        $request->session()->put('server_client_token', $client_token);
        if (!Auth::check()) {
            return redirect('login');
        }
        return redirect()->route('server.login.end');
    }

    public function endLogin(Request $request)
    {
        if(!$request->session()->has('server_client_token') || !Auth::check()) {
            abort(500, 'Invalid state');
        }

        $client_token = $request->session()->get('server_client_token');
        
        if($request->user()->byond_key == null) {
            return view('auth.server.nokey');
        }
        $query = New ServerQuery();
        try {
            $query->setUp(config('aurora.gameserver_address'),config('aurora.gameserver_port'),config('aurora.gameserver_auth'));
            $query->runQuery([
                'query' => 'auth_client',
                'clienttoken' => $client_token,
                'key' => $request->user()->byond_key
            ]);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
        $request->session()->forget('server_client_token');
        if ($query->response->statuscode == '200') {
            return view('auth.server.success');
        } else {
            abort($query->response->statuscode,$query->response);
        }
    }
}
