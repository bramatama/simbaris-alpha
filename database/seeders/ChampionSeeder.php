<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChampionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Data Kategori Juara (Grup)
        $kategoriId1 = DB::table('champion_categories')->insertGetId([
            'champion_category_name' => 'Juara Peringkat',
        ]);
        $kategoriId2 = DB::table('champion_categories')->insertGetId([
            'champion_category_name' => 'Danton Terbaik',
        ]);
        $kategoriId3 = DB::table('champion_categories')->insertGetId([
            'champion_category_name' => 'PBB Terbaik',
        ]);

        // 2. Data Gelar Juara Spesifik (Terikat pada kategori dan memiliki urutan/ranking)
        DB::table('champions')->insert([
            ['champion_category_id' => $kategoriId1, 'champion_name' => 'Juara Utama 1', 'rank_position' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['champion_category_id' => $kategoriId1, 'champion_name' => 'Juara Utama 2', 'rank_position' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['champion_category_id' => $kategoriId1, 'champion_name' => 'Juara Utama 3', 'rank_position' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['champion_category_id' => $kategoriId1, 'champion_name' => 'Juara Harapan 1', 'rank_position' => 4, 'created_at' => $now, 'updated_at' => $now],
            
            ['champion_category_id' => $kategoriId2, 'champion_name' => 'Danton Terbaik 1', 'rank_position' => 1, 'created_at' => $now, 'updated_at' => $now],
            
            ['champion_category_id' => $kategoriId3, 'champion_name' => 'PBB Terbaik 1', 'rank_position' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}