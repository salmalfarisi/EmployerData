<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Session;
use Carbon\Carbon;

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
			if(Auth::user()->status == false)
			{
				Auth::logout();
				return redirect()->back()->with('error', 'Data user sudah dihapus');
			}
			Session::put('Login', Auth::user()->id);
			return redirect()->route('dashboard')->with('success', 'Berhasil login');
		}
		else
		{
			return redirect()->back()->with('error', 'Gagal Login');
		}
	}
	
	//khusus untuk admin
	public function dashboard()
	{
		$today = Carbon::now();
		$data = DB::table('history_absens')->where('userId', Auth::user()->id)->where('absenmasuk', 'LIKE', $today->format('Y-m-d').'%')->orderBy('absenmasuk', 'DESC')->first();
		$gettime = date('H:i:s', strtotime($today->format('H:i:s')));
		$timestart = date('H:i:s', strtotime("06:00:00"));
		$timeend = date('H:i:s', strtotime("17:00:00"));
		$cekwaktu = false;
		switch($cekwaktu)
		{
			case $data == null and $timestart <= $gettime and $gettime <= $timeend:
				$cekwaktu = true;
			case $data->absenkeluar == null and (date('Y-m-d', strtotime($data->absenmasuk)) == date('Y-m-d', strtotime($today))):
				$cekwaktu = true;
			default:
				$cekwaktu = false;
		}
		
		if(Auth::user()->jabatan == 1)
		{
			$now = Carbon::now();
			$lastmonth = $now->subDay(30);
			$now = Carbon::now();
			$historyabsen = DB::table('history_absens')
							->selectRaw('absenmasuk, absenkeluar, HOUR(absenmasuk) as jam')
							->whereBetween('absenmasuk', 
											[
												$lastmonth->format('Y-m-d')." 00:00:00", 
												$now->format('Y-m-d')." 23:59:59"
											]
							)
							->orderBy('absenmasuk', 'ASC')
							->get();
			
			$chartbar = [];
			$chartpie = [];
			$totaltelat = 0;
			$totalontime = 0;
			$temparray = [];
			foreach($historyabsen as $dataabsen)
			{
				$getjam = (int)$dataabsen->jam;
				$getabsenmasuk = date('Y-m-d', strtotime($dataabsen->absenmasuk));
				if(6 <= $getjam and $getjam <= 7)
				{
					$totalontime++;
				}
				else
				{
					$totaltelat++;
				}
				
				array_push($temparray, $getjam);
			}
			
			$chartbar = [
				'telat' => $totaltelat,
				'ontime' => $totalontime
			];
			
			$chartpie = [];
			sort($temparray);
			$tempangka = $temparray[0];
			$totaltempangka = 0;
			foreach($temparray as $test)
			{
				if($test == $tempangka)
				{
					$totaltempangka++;
				}
				else
				{
					array_push($chartpie, [$tempangka, $totaltempangka]);
					$totaltempangka = 1;
					$tempangka = $test;
				}
			}
			
			return view('content.dashboard', compact('data', 'cekwaktu', 'chartbar', 'chartpie'));
		}
		else
		{
			return view('content.dashboard', compact('data', 'cekwaktu'));			
		}
	}
	
	//proses absensi karyawan
	public function createAbsen($id)
	{
		/*
			kasus : 
			lupa absen masuk (kalo beda hari, auto dibilang gak masuk)
			lupa absen keluar (dalam artian kelebihan hari ini harusnya tidak masalah)
			telat dan ontime (selama masih di hari yang sama, gak ada masalah)
		
			1. cek last data history
			2. kalo ada, cek status terakhir jenis absensi
			3. kalo absen masuk dan beda hari, auto bisa absen keluar disaat bersamaan
			4. kalo absen keluar dan beda hari, bisa absen masuk
			5. kalo kosong, cek jam apabila masih di kurang jam 6 pagi, absensi ditolak
		*/
		$lastdata = DB::table('history_absens')->where('userId', Auth::user()->id)->orderBy('absenmasuk', 'DESC')->first();
		$now = Carbon::now();
		$gettime = date('H:i:s', strtotime($now));
		$timestart = date('H:i:s', strtotime("08:00:00"));
		$timeend = date('H:i:s', strtotime("17:00:00"));
		if($timestart <= $gettime && $gettime <= $timeend)
		{
			$createbaru = true;
			
			$getdate = $lastdata->absenmasuk;
			if($lastdata->absenmasuk != null and date('Y-m-d', strtotime($lastdata->absenmasuk)) == date('Y-m-d'))
			{
				$createbaru = false;
			}
			
			if($createbaru == true)
			{
				DB::beginTransaction();
				try
				{
					if($lastdata->absenkeluar == null)
					{
						DB::table('history_absens')->where('id', $lastdata->id)->update([
							'absenkeluar' => $now->format('Y-m-d H:i:s'),
						]);
					}
					
					DB::table('history_absens')->insert([
						'userId' => Auth::user()->id,
						'absenmasuk' => $now->format('Y-m-d H:i:s'),
					]);
					
					DB::commit();
					
					return redirect()->back()->with('success', 'Absensi berhasil diproses');
				}
				catch(Throwable $e)
				{
					DB::rollback();
					return redirect()->back()->with('error', $e->message());
				}				
			}
			else
			{
				DB::beginTransaction();
				try
				{
					DB::table('history_absens')->where('id', $lastdata->id)->update([
						'absenkeluar' => $now->format('Y-m-d H:i:s'),
					]);
					
					DB::commit();
					
					return redirect()->back()->with('success', 'Absensi berhasil diproses');
				}
				catch(Throwable $e)
				{
					DB::rollback();
					return redirect()->back()->with('error', $e->message());
				}
			}
		}
		elseif($lastdata->absenkeluar == null and (date('Y-m-d', strtotime($lastdata->absenmasuk)) == date('Y-m-d', strtotime($today))))
		{
			DB::table('history_absens')->where('id', $lastdata->id)->update([
				'absenkeluar' => $now->format('Y-m-d H:i:s'),
			]);
		}
		else
		{
			return redirect()->back()->with('danger', 'anda sudah melakukan absensi');
		}
	}
	
	public function logout()
	{
		Auth::logout();
		session::flush();
		return redirect()->route('user.index')->with('success', 'Berhasil Logout');
	}
}
