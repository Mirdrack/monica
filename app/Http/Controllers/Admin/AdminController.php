<?php
namespace Monica\Http\Controllers\Admin;

use Monica\Models\Admin;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Admin\StoreAdminsRequest;
use Monica\Http\Requests\Admin\UpdateAdminsRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Contracts\Auth\Access\Gate;
use Silber\Bouncer\Database\Role;

class AdminController extends Controller
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
     * @var \Monica\Models\Tenant
     */
    protected $admin;

    /**
     * @var \Silber\Bouncer\Database\Role
     */
    protected $role;

    /**
     * @param Auth  $auth
     * @param Gate  $gate
     * @param Admin $admin
     */
    public function __construct(Auth $auth, Gate $gate, Admin $admin, Role $role)
    {
        $this->auth = $auth;
        $this->gate = $gate;
        $this->admin = $admin;
        $this->role = $role;
        $this->auth->shouldUse('admin');
    }

    /**
     * Display a listing of Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }

        $admins = $this->admin->with('roles')->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating new Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $roles = $this->role->get()->pluck('title', 'name');

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created Admin in storage.
     *
     * @param  \Monica\Http\Requests\Admin\StoreAdminsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminsRequest $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $admin = $this->admin->create($request->all());

        foreach ($request->input('roles') as $role) {
            $admin->assign($role);
        }

        return redirect()->route('admin.admins.index');
    }


    /**
     * Show the form for editing Admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('title', 'name');

        $admin = Admin::findOrFail($id);

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update Admin in storage.
     *
     * @param  \Monica\Http\Requests\Admin\UpdateAdminsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminsRequest $request, $id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $admin = $this->admin->findOrFail($id);
        $admin->fill($request->all());
        $admin->save();
        foreach ($admin->roles as $role) {
            $admin->retract($role);
        }
        foreach ($request->input('roles') as $role) {
            $admin->assign($role);
        }

        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove Admin from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.admins.index');
    }

    /**
     * Delete all selected Admin at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $adminsDeleted = 0;
        if ($request->input('ids')) {
            $entries = $this->admin->whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            $adminsDeleted = $entries->count();
        }
        return $adminsDeleted;
    }
}
