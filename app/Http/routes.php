<?php

//Copyright (c) 2016 "Werner Maisl", "Sierra Brown"
//
//This file is part of the Aurora Webinterface
//
//The Aurora Webinterface is free software: you can redistribute it and/or modify
//it under the terms of the GNU Affero General Public License as
//published by the Free Software Foundation, either version 3 of the
//License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Affero General Public License for more details.
//
//You should have received a copy of the GNU Affero General Public License
//along with this program. If not, see <http://www.gnu.org/licenses/>.

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => 'web'], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/', function () {
            return view('welcome');
        });
    });

    Auth::routes(); //Auth Routes
    Route::get('/logout', 'Auth\LoginController@logout');

    //Route for SSO
    Route::any('/login/sso_server/', ['as' => 'login.sso', 'uses' => 'Auth\ServerSSOController@sso_server']);

    Route::get('/home', 'HomeController@index'); //Home Page

    //CCIA Stuff
    Route::group(['prefix' => 'ccia', 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'actionlist'], function () {
            Route::get('', ['as' => 'ccia.actions.index', 'uses' => 'CCIA\ActionController@index']);
            Route::get('/{action_id}/show', ['as' => 'ccia.actions.show.get', 'uses' => 'CCIA\ActionController@getShow']);
            Route::get('/{action_id}/edit', ['as' => 'ccia.actions.edit.get', 'uses' => 'CCIA\ActionController@getEdit']);
            Route::post('/{action_id}/edit', ['as' => 'ccia.actions.edit.post', 'uses' => 'CCIA\ActionController@postEdit']);
            Route::get('/{action_id}/delete', ['as' => 'ccia.actions.delete', 'uses' => 'CCIA\ActionController@delete']);
            Route::post('/{action_id}/linkchar', ['as' => 'ccia.actions.linkchar', 'uses' => 'CCIA\ActionController@linkChar']);
            Route::post('/{action_id}/unlinkchar', ['as' => 'ccia.actions.unlinkchar', 'uses' => 'CCIA\ActionController@unlinkChar']);
            Route::get('/add', ['as' => 'ccia.actions.add.get', 'uses' => 'CCIA\ActionController@getAdd']);
            Route::post('/add', ['as' => 'ccia.actions.add.post', 'uses' => 'CCIA\ActionController@postAdd']);
            Route::get('/data/active', ['as' => 'ccia.actions.data.active', 'uses' => 'CCIA\ActionController@getDataActive']);
            Route::get('/data/all', ['as' => 'ccia.actions.data.all', 'uses' => 'CCIA\ActionController@getDataAll']);
        });
        Route::group(['prefix' => 'generalnotice'], function () {
            Route::get('', ['as' => 'ccia.generalnotice.index', 'uses' => 'CCIA\GeneralNoticeController@index']);
            Route::get('/{generalnotice_id}/show', ['as' => 'ccia.generalnotice.show.get', 'uses' => 'CCIA\GeneralNoticeController@getShow']);
            Route::get('/{generalnotice_id}/edit', ['as' => 'ccia.generalnotice.edit.get', 'uses' => 'CCIA\GeneralNoticeController@getEdit']);
            Route::post('/{generalnotice_id}/edit', ['as' => 'ccia.generalnotice.edit.post', 'uses' => 'CCIA\GeneralNoticeController@postEdit']);
            Route::get('/{generalnotice_id}/delete', ['as' => 'ccia.generalnotice.delete', 'uses' => 'CCIA\GeneralNoticeController@delete']);
            Route::get('/add', ['as' => 'ccia.generalnotice.add.get', 'uses' => 'CCIA\GeneralNoticeController@getAdd']);
            Route::post('/add', ['as' => 'ccia.generalnotice.add.post', 'uses' => 'CCIA\GeneralNoticeController@postAdd']);
            Route::get('/data', ['as' => 'ccia.generalnotice.data', 'uses' => 'CCIA\GeneralNoticeController@getData']);
        });
    });

    //Server Stuff
    Route::group(['prefix' => 'server', 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'chars'], function () {
            Route::get('', ['as' => 'server.chars.index', 'uses' => 'Server\CharController@index']);
            Route::get('/all', ['as' => 'server.chars.index.all', 'uses' => 'Server\CharController@indexAll']);
            Route::get('/{char_id}/show', ['as' => 'server.chars.show.get', 'uses' => 'Server\CharController@getShow']);
            Route::post('/{char_id}/edit/text', ['as' => 'server.chars.edit.text.post', 'uses' => 'Server\CharController@postEditText']);
            Route::post('/{char_id}/edit/name', ['as' => 'server.chars.edit.name.post', 'uses' => 'Server\CharController@postEditName']);
            Route::get('/{char_id}/data/log', ['as' => 'server.chars.data.log', 'uses' => 'Server\CharController@getCharDataLog']);
            Route::get('/data/own', ['as' => 'server.chars.data.own', 'uses' => 'Server\CharController@getCharDataOwn']);
            Route::get('/data/all', ['as' => 'server.chars.data.all', 'uses' => 'Server\CharController@getCharDataAll']);
            Route::get('/data/ckey/{ckey}', ['as' => 'server.chars.data.ckey', 'uses' => 'Server\CharController@getCharDataCkey']);
        });

        Route::group(['prefix' => 'incidents'], function () {
            Route::get('/{incident_id}/show', ['as' => 'server.incidents.show.get', 'uses' => 'Server\IncidentController@getShow']);
            Route::get('/{incident_id}/delete', ['as' => 'server.incidents.delete.get', 'uses' => 'Server\IncidentController@getDelete']);
            Route::get('/data/char/{char_id}', ['as' => 'server.incidents.data.char', 'uses' => 'Server\IncidentController@getIncidentDataChar']);
        });

        Route::group(['prefix' => 'form'], function () {
            Route::get('', ['as' => 'admin.forms.index', 'uses' => 'Server\FormController@index']);
            Route::get('/{form_id}/edit', ['as' => 'admin.forms.edit.get', 'uses' => 'Server\FormController@getEdit']);
            Route::post('/{form_id}/edit', ['as' => 'admin.forms.edit.post', 'uses' => 'Server\FormController@postEdit']);
            Route::get('/{form_id}/delete', ['as' => 'admin.forms.delete', 'uses' => 'Server\FormController@delete']);
            Route::get('/add', ['as' => 'admin.forms.add.get', 'uses' => 'Server\FormController@getAdd']);
            Route::post('/add', ['as' => 'admin.forms.add.post', 'uses' => 'Server\FormController@postAdd']);
            Route::get('/data', ['as' => 'admin.forms.data', 'uses' => 'Server\FormController@getFormData']);
        });

        Route::group(['prefix' => 'library'], function () {
            Route::get('', ['as' => 'server.library.index', 'uses' => 'Server\LibraryController@index']);
            Route::get('/{book_id}/show', ['as' => 'server.library.show.get', 'uses' => 'Server\LibraryController@getShow']);
            Route::get('/{book_id}/edit', ['as' => 'server.library.edit.get', 'uses' => 'Server\LibraryController@getEdit']);
            Route::post('/{book_id}/edit', ['as' => 'server.library.edit.post', 'uses' => 'Server\LibraryController@postEdit']);
            Route::get('/{book_id}/delete', ['as' => 'server.library.delete', 'uses' => 'Server\LibraryController@delete']);
            Route::get('/add', ['as' => 'server.library.add.get', 'uses' => 'Server\LibraryController@getAdd']);
            Route::post('/add', ['as' => 'server.library.add.post', 'uses' => 'Server\LibraryController@postAdd']);
            Route::get('/data', ['as' => 'server.library.data', 'uses' => 'Server\LibraryController@getBookData']);
        });

        Route::group(['prefix' => 'live'], function () {
            Route::get('', ['as' => 'server.live.index', 'uses' => 'Server\LiveController@index']);
            Route::get('/coms', ['as' => 'server.live.coms', 'uses' => 'Server\LiveController@getComoptions']);
            Route::get('/ghosts', ['as' => 'server.live.ghosts', 'uses' => 'Server\LiveController@getGhostoptions']);
        });

        Route::group(['prefix' => 'git'], function () {
            Route::get('', ['as' => 'server.git.index', 'uses' => 'Git\PullController@index']);
            Route::get('/data', ['as' => 'server.git.data', 'uses' => 'Git\PullController@getPullData']);
            Route::get('/pull/{pull_id}', ['as' => 'server.git.pull.get', 'uses' => 'Git\PullController@getPull']);
            Route::get('/pull/{pull_id}/data', ['as' => 'server.git.todo.data', 'uses' => 'Git\PullController@getTodoData']);
            Route::get('/todo/{todo_id}', ['as' => 'server.git.todo.get', 'uses' => 'Git\PullController@getTodo']);
            Route::post('/todo/{todo_id}/comment', ['as' => 'server.git.todo.comment.post', 'uses' => 'Git\PullController@postTodoComment']);
            Route::get('/todo/{todo_id}/data', ['as' => 'server.git.todo.comment.data', 'uses' => 'Git\PullController@getTodoCommentData']);
        });

//        Route::group(['prefix' => 'permissions'], function () {
//            Route::any('', ['as' => 'server.permissions.index', 'uses'=>'Server\PermissionController@index']);
//            Route::get('/{permission_id}/', ['as' => 'server.permissions.show', 'uses'=>'Server\PermissionController@show']);
//            Route::get('/add', ['as' => 'server.permissions.add.get', 'uses'=>'Server\PermissionController@getAdd']);
//            Route::post('/add', ['as' => 'server.permissions.add.get', 'uses'=>'Server\PermissionController@postAdd']);
//            Route::get('/{permission_id}/remove', ['as' => 'server.permissions.remove', 'uses'=>'Server\PermissionController@remove']);
//            Route::get('/{permission_id}/add_flag/{flag}', ['as' => 'servers.permissions.flag.add', 'uses'=>'Server\PermissionController@addFlag']);
//            Route::get('/{permission_id}/remove_flag/{flag}', ['as' => 'servers.permissions.flag.remove', 'uses'=>'Server\PermissionController@removeFlag']);
//        });

        Route::group(['prefix' => 'player'], function () {
            Route::get('', ['as' => 'server.players.index', 'uses' => 'Server\PlayerController@index']);
            Route::get('/ckey/{player_ckey}', ['as' => 'server.players.ckey', 'uses' => 'Server\PlayerController@getCkey']);
            Route::get('/{player_id}/show', ['as' => 'server.players.show', 'uses' => 'Server\PlayerController@show']);
            Route::get('/{player_id}/add_whitelist/{whitelist}', ['as' => 'server.players.whitelist.add', 'uses' => 'Server\PlayerController@addWhitelist']);
            Route::get('/{player_id}/remove_whitelist/{whitelist}', ['as' => 'server.players.whitelist.remove', 'uses' => 'Server\PlayerController@removeWhitelist']);
            Route::get('/{player_id}/warnings_data', ['as' => 'server.players.warnings.data', 'uses' => 'Server\PlayerController@getPlayerWarningsData']);
            Route::get('/{player_id}/notes_data', ['as' => 'server.players.notes.data', 'uses' => 'Server\PlayerController@getPlayerNotesData']);
            Route::get('/data', ['as' => 'server.players.data', 'uses' => 'Server\PlayerController@getPlayerData']);
        });

        Route::group(['prefix' => 'stats'], function () {
            Route::get('/', ['as' => 'server.stats.index', 'uses' => 'Server\StatsController@index']);
            Route::any('/round/', ['as' => 'server.stats.round', 'uses' => 'Server\StatsController@round']);
            Route::any('/duration/}', ['as' => 'server.stats.duration', 'uses' => 'Server\StatsController@duration']);
        });
        Route::get('/whitelist/log', ['as' => 'server.whitelist.log', 'uses' => 'Server\WhitelistLogController@getLog']);
    });

    //Website Stuff
    Route::group(['prefix' => 'site', 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'log', 'middleware' => 'permission.site:site_logs_show'], function () {
            Route::get('web', ['as' => 'site.log.index', 'uses' => 'Site\LogViewerController@index']);
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('', ['as' => 'site.roles.index', 'uses' => 'Site\RoleController@index']);
            Route::get('/add', ['as' => 'site.roles.add.get', 'uses' => 'Site\RoleController@getAdd']);
            Route::post('/add', ['as' => 'site.roles.add.post', 'uses' => 'Site\RoleController@postAdd']);
            Route::get('{role_id}/edit', ['as' => 'site.roles.edit.get', 'uses' => 'Site\RoleController@getEdit']);
            Route::post('{role_id}/edit', ['as' => 'site.roles.edit.post', 'uses' => 'Site\RoleController@postEdit']);
            Route::get('{role_id}/delete', ['as' => 'site.roles.delete', 'uses' => 'Site\RoleController@delete']);
            Route::post('{role_id}/addperm', ['as' => 'site.roles.addperm', 'uses' => 'Site\RoleController@addPermission']);
            Route::post('{role_id}/remperm', ['as' => 'site.roles.remperm', 'uses' => 'Site\RoleController@removePermission']);
            Route::post('{role_id}/adduser', ['as' => 'site.roles.adduser', 'uses' => 'Site\RoleController@addUser']);
            Route::post('{role_id}/remuser', ['as' => 'site.roles.remuser', 'uses' => 'Site\RoleController@removeUser']);
        });
    });

    //Syndie Stuff
    Route::group(['prefix' => 'syndie', 'middleware' => 'auth'], function () {
        //Contract DB
        Route::group(['prefix' => 'contracts'], function () {
            Route::get('', ['as' => 'syndie.contracts.index', 'uses' => 'Syndie\ContractController@index']);
            Route::get('/{contract}/show', ['as' => 'syndie.contracts.show', 'uses' => 'Syndie\ContractController@show']);
            Route::get('/add', ['as' => 'syndie.contracts.add.get', 'uses' => 'Syndie\ContractController@getAdd']);
            Route::post('/add', ['as' => 'syndie.contracts.add.post', 'uses' => 'Syndie\ContractController@postAdd']);
            Route::get('/{contract}/edit', ['as' => 'syndie.contracts.edit.get', 'uses' => 'Syndie\ContractController@getEdit']);
            Route::post('/{contract}/edit', ['as' => 'syndie.contracts.edit.post', 'uses' => 'Syndie\ContractController@postEdit']);
            Route::get('/{contract}/approve', ['as' => 'syndie.contracts.approve', 'uses' => 'Syndie\ContractController@approve']); //Mod Approve the contract
            Route::get('/{contract}/reject', ['as' => 'syndie.contracts.reject', 'uses' => 'Syndie\ContractController@reject']); //Mod Reject the contract
            Route::get('/{contract}/delete', ['as' => 'syndie.contracts.deletecontract', 'uses' => 'Syndie\ContractController@delete']);
            Route::get('/data', ['as' => 'syndie.contracts.data', 'uses' => 'Syndie\ContractController@getContractData']);
            Route::get('/{contract}/subscribe', ['as' => 'syndie.contracts.subscribe', 'uses' => 'Syndie\ContractController@subscribe']);
            Route::get('/{contract}/unsubscribe', ['as' => 'syndie.contracts.unsubscribe', 'uses' => 'Syndie\ContractController@unsubscribe']);
        });

        Route::group(['prefix' => 'comments'], function () {
            Route::get('', ['as' => 'syndie.comments.index', 'uses' => 'Syndie\ContractComment@index']);
            Route::get('/{contract}/add', ['as' => 'syndie.comments.add.get', 'uses' => 'Syndie\ContractComment@getAdd']);
            Route::post('/{contract}/add', ['as' => 'syndie.comments.add.post', 'uses' => 'Syndie\ContractComment@postAdd']);
//            Route::get('/{comment}/edit', ['as' => 'syndie.comments.edit.get', 'uses'=>'Syndie\ContractComment@getEdit']);
//            Route::post('/{comment}/edit', ['as' => 'syndie.comments.edit.post', 'uses'=>'Syndie\ContractComment@postEdit']);
            Route::get('/{comment}/confirmopen', ['as' => 'syndie.comments.confirmopen', 'uses' => 'Syndie\ContractComment@confirmopen']); // Confirm completion and leave the contract open
            Route::get('/{comment}/confirmclose', ['as' => 'syndie.comments.confirmclose', 'uses' => 'Syndie\ContractComment@confirmclose']); // Confirm completion and close the contract
            Route::get('/{comment}/reject', ['as' => 'syndie.comments.reject', 'uses' => 'Syndie\ContractComment@reject']); // Confirm completion and close the contract
            Route::get('/{comment}/delete', ['as' => 'syndie.comments.delete', 'uses' => 'Syndie\ContractComment@delete']); //Delete a comment
        });

        Route::group(['prefix' => 'objectives'], function () {
            Route::get('/{contract}/add', ['as' => 'syndie.objectives.add.get', 'uses' => 'Syndie\ContractObjective@getAdd']);
            Route::post('/{contract}/add', ['as' => 'syndie.objectives.add.post', 'uses' => 'Syndie\ContractObjective@postAdd']);
            Route::get('/{objective}/', ['as' => 'syndie.objectives.show', 'uses' => 'Syndie\ContractObjective@view']);
            Route::get('/{objective}/edit', ['as' => 'syndie.objectives.edit.get', 'uses' => 'Syndie\ContractObjective@getEdit']);
            Route::post('/{objective}/edit', ['as' => 'syndie.objectives.edit.post', 'uses' => 'Syndie\ContractObjective@postEdit']);
            Route::get('/{objective}/close', ['as' => 'syndie.objectives.close', 'uses' => 'Syndie\ContractObjective@close']);
            Route::get('/{objective}/open', ['as' => 'syndie.objectives.open', 'uses' => 'Syndie\ContractObjective@open']);
            Route::get('/{objective}/delete', ['as' => 'syndie.objectives.delete', 'uses' => 'Syndie\ContractObjective@delete']);
        });

        Route::group(['prefix' => 'contest'], function () {
            Route::get('/', ['as' => 'syndie.contest.view', 'uses' => 'Syndie\ContestController@index']);
            Route::get('/factions/', ['as' => 'syndie.contest.factions.get', 'uses' => 'Syndie\ContestController@getFactions']);
            Route::get('/reports/', ['as' => 'syndie.contest.reports.get', 'uses' => 'Syndie\ContestController@getReports']);
        });

        Route::get('/api/agentlist', ['as' => 'syndie.api.get.agentlist', 'uses' => 'Syndie\ContractController@getAgentList']);
    });

    //User Stuff
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
        //User Dashboard
        Route::get('/', ['as' => 'user.dashboard', 'uses' => 'User\DashboardController@index']);
        //User Linking
        Route::group(['prefix' => 'link'], function () {
            Route::get('/', ['as' => 'user.link', 'uses' => 'User\LinkController@index']);
            Route::post('/add', ['as' => 'user.link.add', 'uses' => 'User\LinkController@add']);
            Route::get('/cancel', ['as' => 'user.link.cancel', 'uses' => 'User\LinkController@cancel']);
        });
        Route::get('/warnings', ['as' => 'user.warnings', 'uses' => 'User\WarningController@index']);
    });
});

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    Route::group(['prefix' => 'server', 'middleware' => 'auth'], function () {
        Route::get('/live/faxmachines', ['as' => 'api.server.live.get.faxmachines', 'uses' => 'Server\LiveController@getFaxmachines']);
        Route::get('/live/ghosts', ['as' => 'api.server.live.get.ghosts', 'uses' => 'Server\LiveController@getGhosts']);
        Route::post('/live/sendfax', ['as' => 'api.server.live.post.sendfax', 'uses' => 'Server\LiveController@postSendfax']);
        Route::post('/live/sendreport', ['as' => 'api.server.live.post.sendreport', 'uses' => 'Server\LiveController@postSendreport']);
        Route::post('/live/grantrespawn', ['as' => 'api.server.live.post.grantrespawn', 'uses' => 'Server\LiveController@postGrantrespawn']);
    });

    Route::group(['prefix' => 'git'], function () {
        Route::any('/', ['as' => 'api.git.index', 'uses' => 'Git\HookController@index']);
    });
});