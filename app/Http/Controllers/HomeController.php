<?php
namespace Monica\Http\Controllers;

use Monica\Models\Tenant;

class HomeController extends Controller
{
    /**
     * @param \Monica\Models\Tenant $tenant
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Show the application dashboard.
     * @param  string $subdomain
     * @return \Illuminate\Http\Response
     */
    public function index($subdomain)
    {
        $tenant = $this->tenant->where('subdomain', $subdomain)->first();
        if ($tenant) {
            return view('home', compact('tenant'));
        }
        return response()->view('errors.404', [], 404);
    }
}
