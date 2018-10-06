<?php

namespace Monica\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Monica\Models\Tenant;
use Monica\Http\Controllers\Controller;
use Monica\Http\Requests\Tenant\StoreTenantsRequest;
use Monica\Http\Requests\Tenant\UpdateTenantsRequest;

use Monica\Service\Admin\TenantService;

class TenantController extends Controller
{
    /**
     * Handler of auth functions
     * @var \Illuminate\Auth\AuthManager
     */
    protected $authManager;

    /**
     * Framework permission handler
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    protected $gate;

    /**
     * Tenant service
     * @var \Monica\Service\Admin\TenantService
     */
    protected $tenantService;

    public function __construct(
        AuthManager $authManager,
        Gate $gate,
        TenantService $tenantService
    )
    {
        $this->authManager = $authManager;
        $this->gate = $gate;
        $this->tenantService = $tenantService;
        $this->authManager->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $tenants = Tenant::all();
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Monica\Http\Requests\Tenant\StoreTenantsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantsRequest $request)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $this->tenantService->registerTenant($request->all());
        return redirect()->route('admin.tenants.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $tenant = Tenant::findOrFail($id);
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Monica\Http\Requests\Tenant\UpdateTenantsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTenantsRequest $request, $id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $tenant = Tenant::findOrFail($id);
        $tenant->fill($request->all());
        $tenant->save();
        return redirect()->route('admin.tenants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->gate->allows('admins_manage')) {
            return abort(401);
        }
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.tenants.index');
    }
}
