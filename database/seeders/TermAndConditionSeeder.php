<?php

namespace Database\Seeders;

use App\Models\TermAndCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        TermAndCondition::create([
            'name' => 'Be at least 18 years old or meet the legal gambling age in your jurisdiction.
Have the legal capacity to enter into binding agreements.
Use the App in accordance with the laws of your country or region.',
        ]);
    }
}
