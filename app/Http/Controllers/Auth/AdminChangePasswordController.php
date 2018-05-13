<?php

namespace Monica\Http\Controllers\Auth;

use Monica\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Validator;

class AdminChangePasswordController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Where to redirect admins after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/change_password';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        $admin = Auth::getUser();

        return view('auth.admin_change_password', compact('admin'));
    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $admin = Auth::getUser();
        $this->validator($request->all())->validate();
        if (Hash::check($request->get('current_password'), $admin->password)) {
            $admin->password = $request->get('new_password');
            $admin->save();
            return redirect($this->redirectTo)->with('success', 'Password change successfully!');
        } else {
            return redirect()->back()->withErrors('Current password is incorrect');
        }
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}