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

class UserController extends Controller
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
     * @var Monica\Models\Tenant
     */
    protected $tenant;

    /**
     * @var Monica\Models\User
     */
    protected $user;

    /**
     * @var Silber\Bouncer\Database\Role
     */
    protected $role;

    /**
     * Sets auth manager and permission gate and the middlewares
     * @param Auth $auth
     * @param Gate $gate
     * @param Tenant $tenant
     * @param User   $user
     * @param Role   $role
     */
    public function __construct(Auth $auth, Gate $gate, Tenant $tenant, User $user, Role $role)
    {
        $this->auth = $auth;
        $this->gate = $gate;
        $this->tenant = $tenant;
        $this->user = $user;
        $this->role = $role;
        $this->auth->guard('web');
    }

    /**
     * Display a listing of User.
     * @param  string $subdomain tenant subdomain
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function index($subdomain)
    {
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $tenant = $this->tenant->where('subdomain', $subdomain)->first();
        if ($tenant) {
            $users = $this->user->with('roles')
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

        $tenant = $this->tenant->where('subdomain', $subdomain)->first();
        if ($tenant) {
            $roles = $this->role->get()->pluck('title', 'name');
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

        $tenant = $this->tenant->where('subdomain', $subdomain)->first();
        if ($tenant) {
            $user = $this->user->create($request->all());
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
        $tenant = $this->tenant->where('subdomain', $subdomain)->first();

        if ($tenant) {
            $roles = $this->role->get()->pluck('title', 'name');

            $user = $this->user->findOrFail($id);

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
        $user = $this->user->findOrFail($id);
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
        $user = $this->user->findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.users.index', $subdomain);
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        // TO DO: Allow deleted only for the current tenant
        if (! $this->gate->allows('users_manage')) {
            return abort(401);
        }
        $usersDeleted = 0;
        if ($request->input('ids')) {
            $entries = $this->user->whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            $usersDeleted = $entries->count();
        }
        return $usersDeleted;
    }
}
