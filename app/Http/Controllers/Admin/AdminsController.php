<?php
namespace Monica\Http\Controllers\Admin;

use Monica\Models\Admin;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Admin\StoreAdminsRequest;
use Monica\Http\Requests\Admin\UpdateAdminsRequest;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('admin');
    }
    
    /**
     * Display a listing of Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }

        $admins = Admin::with('roles')->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating new Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created Admin in storage.
     *
     * @param  \Monica\Http\Requests\StoreAdminsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminsRequest $request)
    {
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        $admin = Admin::create($request->all());

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
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        $admin = Admin::findOrFail($id);

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update Admin in storage.
     *
     * @param  \Monica\Http\Requests\UpdateAdminsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminsRequest $request, $id)
    {
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        $admin = Admin::findOrFail($id);
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
        if (! Gate::allows('admins_manage')) {
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
        if (! Gate::allows('admins_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Admin::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}
