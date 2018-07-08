<?php

namespace Monica\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Auth\AuthManager as Auth;
use Monica\Models\User;
use Monica\Models\Tenant;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\User\StoreUsersRequest;
use Monica\Http\Requests\User\UpdateUsersRequest;
use Silber\Bouncer\Database\Role;

class UsersController extends Controller
{
    /**
     * Handle authentication functions
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Checks the user permissions
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    protected $gate;

    /**
     * Sets auth manager and permission gate and the middlewares
     * @param Auth $auth
     * @param Gate $gate
     */
    public function __construct(Auth $auth, Gate $gate)
    {
        $this->auth = $auth;
        $this->gate = $gate;
        $this->auth->guard('web');
    }
    
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subdomain)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $tenant = Tenant::where('subdomain', $subdomain)->first();
        if ($tenant) {
            $users = User::with('roles')
                        ->where('tenant_id', $tenant->id)
                        ->get();
            return view('dashboard.users.index', compact('users', 'tenant'));
        }
        return abort(404);
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($subdomain)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();
        if ($tenant) {
            $roles = Role::get()->pluck('name', 'name');
            return view('dashboard.users.create', compact('roles', 'tenant'));
        }
        return abort(404);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Monica\Http\Requests\User\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request, $subdomain)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();
        if ($tenant) {
            $user = User::create($request->all());
            foreach ($request->input('roles') as $role) {
                $user->assign($role);
            }
            return redirect()->route('dashboard.users.index', $subdomain);
        }
        return abort(404);
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($subdomain, $id)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if ($tenant) {
            $roles = Role::get()->pluck('name', 'name');

            $user = User::findOrFail($id);

            return view('dashboard.users.edit', compact('user', 'roles', 'tenant'));
        }
        return abort(404);

    }

    /**
     * Update User in storage.
     *
     * @param  \Monica\Http\Requests\User\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $subdomain, $id)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->update($request->all());
        foreach ($user->roles as $role) {
            $user->retract($role);
        }
        foreach ($request->input('roles') as $role) {
            $user->assign($role);
        }

        return redirect()->route('dashboard.users.index', $subdomain);
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($subdomain, $id)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.users.index', $subdomain);
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy($subdomain, Request $request)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}
