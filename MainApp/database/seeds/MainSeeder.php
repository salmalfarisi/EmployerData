<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
		DB::table('history_absens')->truncate();
        DB::table('users')->truncate();
		DB::table('jabatans')->truncate();
		DB::table('kantor_cabangs')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		
		$now = Carbon::now();
		$start = date('Y-m-d', strtotime($now->subDay(30)));
		
		$array1 = ['Kantor Pusat', 'Cabang Bandung', 'Cabang Surabaya', 'Cabang Makassar'];
		$array2 = ['Jakarta', 'Bandung', 'Surabaya', 'Makassar'];
		$array3 = ['Administrasi','Manager', 'Marketing', 'Staff'];
		
		for($i = 0; $i < 4; $i++)
		{
			DB::table('kantor_cabangs')->insert([
				'nama_kantor' => $array1[$i],
				'kota' => $array2[$i],
			]);
			
			DB::table('jabatans')->insert(['jabatan' => $array3[$i]]);
		}
		
		/*
		$table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->foreignId('jabatan')->constrained('jabatans');
            $table->date('tgl_lahir');
            $table->text('alamat');
			$table->foreignId('kantor_cabang')->constrained('kantor_cabangs');
		*/
		
		for($i = 1; $i <= 5; $i++)
		{
			if($i == 1)
			{
				$password = Hash::make('admin');
				DB::table('users')->insert([
					'name' => 'admin',
					'email' => 'admin@admin.com',
					'password' => $password,
					'jabatan' => 1,
					'tgl_lahir' => '1999-01-01',
					'alamat' => 'admin',
					'kantor_cabang' => 1,
					'status' => true
				]);
			}
			else
			{
				$tanggal = $i;
				$name = 'User'.$i;
				$password = Hash::make($name);
				DB::table('users')->insert([
					'name' => $name,
					'email' => $name.'@email.com',
					'password' => $password,
					'jabatan' => rand(2,4),
					'tgl_lahir' => '2000-01-'.$tanggal,
					'alamat' => 'Alamat ke '.$i,
					'kantor_cabang' => rand(1,4),
					'status' => true
				]);
			}
			
			$temptanggal = $start;
			for($j = 1; $j < 30; $j++)
			{
				$getname = Carbon::parse($temptanggal)->dayName;
				if($getname != 'Saturday' and $getname != 'Sunday')
				{
					$setabsenmasuk = $temptanggal.' '.rand(6,17).':'.rand(0,59).':'.rand(0,59);
					$setabsenkeluar = $temptanggal.' '.rand(17,23).':'.rand(0,59).':'.rand(0,59);
					DB::table('history_absens')->insert([
						'userId' => $i,
						'absenmasuk' => $setabsenmasuk,
						'absenkeluar' => $setabsenkeluar,
					]);
				}
				$temptanggal = date('Y-m-d', strtotime($temptanggal.'+1 day'));
			}
		}
    }
}
