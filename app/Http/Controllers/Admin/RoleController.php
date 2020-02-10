<?php
namespace Monica\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Auth\AuthManager as Auth;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Admin\StoreRolesRequest;
use Monica\Http\Requests\Admin\UpdateRolesRequest;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class RoleController extends Controller
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
     * @var \Silber\Bouncer\Database\Role
     */
    protected $role;

    public function __construct(Auth $auth, Gate $gate, Role $role)
    {
        $this->auth = $auth;
        $this->gate = $gate;
        $this->role = $role;
        $this->auth->shouldUse('admin');
    }

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }

        $roles = $this->role->all();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $abilities = Ability::get()->pluck('title', 'name');

        return view('admin.roles.create', compact('abilities'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \Monica\Http\Requests\Admin\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRolesRequest $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $role = Role::create($request->all());
        $role->allow($request->input('abilities'));

        return redirect()->route('admin.roles.index');
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $abilities = Ability::get()->pluck('title', 'name');

        $role = Role::findOrFail($id);

        return view('admin.roles.edit', compact('role', 'abilities'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \Monica\Http\Requests\Admin\UpdateRolesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolesRequest $request, $id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $role = $this->role->findOrFail($id);
        $role->update($request->all());
        foreach ($role->getAbilities() as $ability) {
            $role->disallow($ability->name);
        }
        $role->allow($request->input('abilities'));

        return redirect()->route('admin.roles.index');
    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index');
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $deleteRoles = 0;
        if ($request->input('ids')) {
            $entries = $this->role->whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            $deleteRoles = $entries->count();
        }
        return $deleteRoles;
    }
}
