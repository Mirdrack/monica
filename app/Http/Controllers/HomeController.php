<?php
namespace Monica\Http\Controllers;

use Home\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monica\Models\Tenant;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->first();
        if ($tenant) {
            return view('home', compact('tenant'));
        }
    }
}