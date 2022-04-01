<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Session;

class UserController extends Controller
{
    public function index()
	{
		if(session('Login') != null)
		{
			return redirect()->route('karyawan.index');
		}
		return view('content.login');
	}
	
	public function store(Request $request)
	{
		$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
		
		$array = ['email' => $request->email, 'password' => $request->password];
		
		if (Auth::attempt($array)) 
		{
			Session::put('Login', Auth::user()->id);
			return redirect()->route('karyawan.index')->with('success', 'Berhasil login');
		}
		else
		{
			return redirect()->back()->with('error', 'Gagal Login');
		}
	}
	
	public function logout()
	{
		Auth::logout();
		session::flush();
		return redirect()->route('user.index')->with('success', 'Berhasil Logout');
	}
}
