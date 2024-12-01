<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names=['Deposit','Withdrawal'];
        foreach($names as $name){
            TransactionType::firstOrCreate([
                'name'=>$name,
            ],
                [
                'name'=>$name,
                'min'=>1000,
                'max'=>1000000,
            ]);
        }
    }
}
