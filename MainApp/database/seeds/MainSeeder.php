<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
		DB::table('karyawans')->truncate();
		DB::table('jabatans')->truncate();
		DB::table('kantor_cabangs')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		
		$password = Hash::make('admin');
        DB::table('users')->insert([
				'name' => 'admin',
				'email' => 'admin@admin.com',
				'password' => $password,
		]);
		
		$array1 = ['Kantor Pusat', 'Cabang Bandung', 'Cabang Surabaya', 'Cabang Makassar'];
		$array2 = ['Jakarta', 'Bandung', 'Surabaya', 'Makassar'];
		$array3 = ['Manager', 'Marketing', 'Administrasi', 'Security'];
		
		for($i = 0; $i < 4; $i++)
		{
			DB::table('kantor_cabangs')->insert([
				'nama_kantor' => $array1[$i],
				'kota' => $array2[$i],
			]);
			
			DB::table('jabatans')->insert(['jabatan' => $array3[$i]]);
		}
		
		/* for($i = 0; $i < 4; $i++)
		{
			$tanggal = $i+1;
			DB::table('karyawans')->insert([
				'nama_karyawan' => 'User '.$i,
				'jabatan' => rand(1,4),
				'tgl_lahir' => '2000-01-'.$tanggal,
				'alamat' => 'Alamat ke '.$i,
				'kantor_cabang' => rand(1,4),
			]);
		} */
    }
}
