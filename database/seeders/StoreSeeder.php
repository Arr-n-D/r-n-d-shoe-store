<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            'ALDO Centre Eaton',
            'ALDO Destiny USA Mall',
            'ALDO Pheasant Lane Mall',
            'ALDO Holyoke Mall',
            'ALDO Maine Mall',
            'ALDO Crossgates Mall',
            'ALDO Burlington Mall',
            'ALDO Solomon Pond Mall',
            'ALDO Auburn Mall',
            'ALDO Waterloo Premium Outlets'
        ];

        foreach ($stores as $store) {
            DB::table('stores')->insert([
                'name' => $store,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
