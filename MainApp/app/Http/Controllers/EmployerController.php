<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User as Karyawan;
use date;
use Carbon\Carbon;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('users')
					->select('users.id as id', 'users.name as nama_karyawan', 'jabatans.jabatan as jabatan', 'kantor_cabangs.nama_kantor as nama_kantor', 'users.status as status')
					->leftJoin('jabatans', 'jabatans.id', '=', 'users.jabatan')
					->leftJoin('kantor_cabangs', 'kantor_cabangs.id', '=', 'users.kantor_cabang')
					->where('jabatans.jabatan', '<>', 'Administrasi')
					->orderBy('users.created_at','DESC')
					->paginate(20);
					
		$count = DB::table('users')->where('jabatan', '<>', 3)->count();
		
		return view('content.index', compact('data', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new Karyawan();
		$kantor_cabang = DB::table('kantor_cabangs')->get();
		$jabatan = DB::table('jabatans')->get();
		$title = 'create';
		return view('content.form', compact('data', 'kantor_cabang', 'jabatan', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
			'nama_karyawan' => 'required|string|max:100',
			'jabatan' => 'required',
			'tgl_lahir' => 'required',
			'alamat' => 'string|nullable',
			'kantor_cabang' => 'required',
		]);
		
		$now = Carbon::now();
		DB::beginTransaction();
		try
		{
			$password = hash::make($request->nama_karyawan);
			DB::table('users')->insert([
				'password' => $password,
				'name' => $request->nama_karyawan,
				'jabatan' => $request->jabatan,
				'tgl_lahir' => $request->tgl_lahir,
				'alamat' => $request->alamat,
				'kantor_cabang' => $request->kantor_cabang,
				'created_at' => $now
			]);
			
			DB::commit();
			
			return redirect()->route('karyawan.index')->with('success', 'Data berhasil bertambah');
		}
		catch(Throwable $e)
		{
			DB::rollback();
			return redirect()->back()->with('error', $e->message());
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('users')->where('id', $id)->first();
		$kantor_cabang = DB::table('kantor_cabangs')->get();
		$jabatan = DB::table('jabatans')->get();
		
		$now = Carbon::now();
		$lastmonth = $now->subDay(30);
		$now = Carbon::now();
		$historyabsen = DB::table('history_absens')
							->selectRaw('absenmasuk, absenkeluar, HOUR(absenmasuk) as jam')
							->where('userId',$id)
							->whereBetween('absenmasuk', 
											[
												$lastmonth->format('Y-m-d')." 00:00:00", 
												$now->format('Y-m-d')." 23:59:59"
											]
							)
							->orderBy('absenmasuk', 'ASC')
							->get();
		
		$temparray = [];
		$chartline = [];
		$totaltelat = 0;
		$totalontime = 0;
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
			array_push($chartline, [$getabsenmasuk, $getjam]);
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
		
		$title = 'detail';
		return view('content.form', compact('data', 'kantor_cabang', 'jabatan', 'title', 'chartpie', 'chartbar', 'chartline'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('users')->where('id', $id)->first();
		$kantor_cabang = DB::table('kantor_cabangs')->get();
		$jabatan = DB::table('jabatans')->get();
		$title = 'edit';
		return view('content.form', compact('data', 'kantor_cabang', 'jabatan', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
			'nama_karyawan' => 'required|string|max:100',
			'jabatan' => 'required',
			'tgl_lahir' => 'required',
			'alamat' => 'string|nullable',
			'kantor_cabang' => 'required',
		]);
		
		$now = Carbon::now();
		DB::beginTransaction();
		try
		{
			DB::table('users')->where('id', $id)->update([
				'name' => $request->nama_karyawan,
				'jabatan' => $request->jabatan,
				'tgl_lahir' => $request->tgl_lahir,
				'alamat' => $request->alamat,
				'kantor_cabang' => $request->kantor_cabang,
				'updated_at' => $now
			]);
			
			DB::commit();
			
			return redirect()->route('karyawan.index')->with('success', 'Data berhasil berubah');
		}
		catch(Throwable $e)
		{
			DB::rollback();
			return redirect()->back()->with('error', $e->message());
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$now = Carbon::now();
		DB::beginTransaction();
		try
		{
			DB::table('users')->where('id', $id)->update([
				'status' => false
			]);
			
			DB::commit();
			
			return redirect()->route('karyawan.index')->with('success', 'Data berhasil dihapus');
		}
		catch(Throwable $e)
		{
			DB::rollback();
			return redirect()->back()->with('error', $e->message());
		}
    }
	
	public function getHistory($id)
	{
		$database = DB::table('history_absens')->where('userId', $id)->orderBy('absenmasuk', 'DESC')->get();
		$data = [];
		$loop = 1;
		foreach($database as $listdata)
		{
			$gettime = date('H:i:s', strtotime($listdata->absenmasuk));
			$timestart = date('H:i:s', strtotime("06:00:00"));
			$timeend = date('H:i:s', strtotime("08:00:00"));
			if($timestart <= $gettime && $gettime <= $timeend)
			{
				$statuslambat = 'Tidak';
			}
			else
			{
				$statuslambat = 'Ya';
			}
		
			$setdata = [
				'no' => $loop,
				'tanggal' => date('Y-m-d', strtotime($listdata->absenmasuk)),
				'absenmasuk' => date('H:i:s', strtotime($listdata->absenmasuk)),
				'absenkeluar' => date('H:i:s', strtotime($listdata->absenkeluar)),
				'status' => $statuslambat
			];
			$setdata = (object)$setdata;
			array_push($data, $setdata);
			$loop++;
		}
		return response()->json(['data' => $data]);
	}
}
