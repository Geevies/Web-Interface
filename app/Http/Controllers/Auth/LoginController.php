<?php

/**
 * Copyright (c) 2016 "Werner Maisl"
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Auth\ForumUserModel;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Logs the user in by supplying a username and a login token
     */
    public function sso_server(Request $request)
    {
        $ckey_in = NULL;
        $token_in = NULL;
        $location = NULL;

        //Get the token and the ckey from the request
        $ckey_in = $request->input('ckey');
        $token_in = $request->input('token');
        $location = $request->input('location');
        $user_ip = $_SERVER['REMOTE_ADDR'];

        if(Auth::check())
        {
            $this->redirect_after_sso($location,$request);
        }

        if ($ckey_in == NULL | $token_in == NULL) abort(404);

        //Check if a sso entry for the ckey and token exists
        $valid_until = Carbon::now();
        $valid_until->subHours(config('aurora.token_valid_time'));
        $count = DB::connection('server')->table('web_sso')
            ->where('ckey', $ckey_in)
            ->where('token', $token_in)
            ->where('ip', $user_ip)
            ->where('created_at', '>', $valid_until->toDateTimeString())
            ->count();
        if ($count == 0) abort(404);

        //Check if a user with a linked byond account exists in the forum db
        $user = ForumUserModel::where('user_byond', $ckey_in)->first();

        if ($user == NULL) {
            return view('errors.no_user_linked', array('ckey' => $ckey_in));
        }

        //Auth the user
        Auth::login($user);

        //Delete the sso entry from the db
        DB::connection('server')->table('web_sso')->where('ckey', $ckey_in)->delete();

        $this->redirect_after_sso($location,$request);
    }

    private function redirect_after_sso($location, Request $request)
    {
        //Redirect User to Destination
        switch ($location) {
            case "user_dashboard":
                return redirect()->route('user.dashboard');
                break;
            case "contract_overview":
                return redirect()->route('syndie.contracts.index');
                break;
            case "contract_details":
                return redirect()->route('syndie.contracts.show', ['contract' => $request->input('contract')]);
                break;
            case "security_incident":
                return redirect()->route('server.incidents.show.get', ['contract' => $request->input('incident')]);
                break;
            default:
                return redirect()->route('user.dashboard');
                break;
        }
    }
}
