<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShoeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shoes = [
            'ADERI',
            'MIRIRA',
            'CAELAN',
            'BUTAUD',
            'SCHOOLER',
            'SODANO',
            'MCTYRE',
            'CADAUDIA',
            'RASIEN',
            'WUMA',
            'GRELIDIEN',
            'CADEVEN',
            'SEVIDE',
            'ELOILLAN',
            'BEODA',
            'VENDOGNUS',
            'ABOEN',
            'ALALIWEN',
            'GREG',
            'BOZZA'
        ];

        foreach ($shoes as $shoe) {
            DB::table('shoes')->insert([
                'name' => $shoe,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
