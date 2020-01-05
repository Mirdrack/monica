<?php
namespace Monica\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Auth\AuthManager as Auth;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Admin\StoreAbilitiesRequest;
use Monica\Http\Requests\Admin\UpdateAbilitiesRequest;
use Silber\Bouncer\Database\Ability;

class AbilityController extends Controller
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
     * @var Silber\Bouncer\Database\Ability
     */
    protected $ability;

    public function __construct(Auth $auth, Gate $gate, Ability $ability)
    {
        $this->auth = $auth;
        $this->gate = $gate;
        $this->ability = $ability;
        $this->auth->shouldUse('admin');
    }

    /**
     * Display a listing of Abilities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }

        $abilities = $this->ability->all();

        return view('admin.abilities.index', compact('abilities'));
    }

    /**
     * Show the form for creating new Ability.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        return view('admin.abilities.create');
    }

    /**
     * Store a newly created Ability in storage.
     *
     * @param  \Monica\Http\Requests\Admin\StoreAbilitiesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAbilitiesRequest $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $this->ability->create($request->all());

        return redirect()->route('admin.abilities.index');
    }

    /**
     * Show the form for editing Ability.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $ability = $this->ability->findOrFail($id);

        return view('admin.abilities.edit', compact('ability'));
    }

    /**
     * Update Ability in storage.
     *
     * @param  \Monica\Http\Requests\Admin\UpdateAbilitiesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAbilitiesRequest $request, $id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $ability = $this->ability->findOrFail($id);
        $ability->update($request->all());

        return redirect()->route('admin.abilities.index');
    }

    /**
     * Remove Ability from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $ability = $this->ability->findOrFail($id);
        $ability->delete();

        return redirect()->route('admin.abilities.index');
    }

    /**
     * Delete all selected Ability at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = $this->ability->whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}
