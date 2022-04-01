<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Karyawan as Karyawan;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('karyawans')
					->select('karyawans.id as id', 'karyawans.nama_karyawan as nama_karyawan', 'jabatans.jabatan as jabatan', 'kantor_cabangs.nama_kantor as nama_kantor')
					->leftJoin('jabatans', 'jabatans.id', '=', 'karyawans.jabatan')
					->leftJoin('kantor_cabangs', 'kantor_cabangs.id', '=', 'karyawans.kantor_cabang')
					->paginate(20);
					
		$count = DB::table('karyawans')->count();
		
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
		
		DB::table('karyawans')->insert([
			'nama_karyawan' => $request->nama_karyawan,
			'jabatan' => $request->jabatan,
			'tgl_lahir' => $request->tgl_lahir,
			'alamat' => $request->alamat,
			'kantor_cabang' => $request->kantor_cabang,
		]);
		
		return redirect()->route('karyawan.index')->with('success', 'Data berhasil bertambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('karyawans')->where('id', $id)->first();
		$kantor_cabang = DB::table('kantor_cabangs')->get();
		$jabatan = DB::table('jabatans')->get();
		$title = 'detail';
		return view('content.form', compact('data', 'kantor_cabang', 'jabatan', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('karyawans')->where('id', $id)->first();
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
		
		DB::table('karyawans')->where('id', $id)->update([
			'nama_karyawan' => $request->nama_karyawan,
			'jabatan' => $request->jabatan,
			'tgl_lahir' => $request->tgl_lahir,
			'alamat' => $request->alamat,
			'kantor_cabang' => $request->kantor_cabang,
		]);
		
		return redirect()->route('karyawan.index')->with('success', 'Data berhasil update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('karyawans')->where('id', $id)->delete();
		return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
