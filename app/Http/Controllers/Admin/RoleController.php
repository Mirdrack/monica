<?php
namespace Monica\Http\Controllers\Admin;

use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Admin\StoreRolesRequest;
use Monica\Http\Requests\Admin\UpdateRolesRequest;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('admin');
    }

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('admins_manage')) {
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
        if (! Gate::allows('admins_manage')) {
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
        if (! Gate::allows('admins_manage')) {
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
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        $role = Role::findOrFail($id);
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
        if (! Gate::allows('admins_manage')) {
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
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}
