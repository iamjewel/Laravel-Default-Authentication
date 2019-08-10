<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hasHadPassword = Auth::user()->password;

        if (Hash::check($request->oldPassword, $hasHadPassword)) {
            $user = User::find(Auth::id());

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();

            return redirect('/login')->with('successMessage', 'Password Changed Successfully');
        } else {
            return redirect()->back()->with('errorMessage','Current Password Is Invalid');
        }
    }
}
