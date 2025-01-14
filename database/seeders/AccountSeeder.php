<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $accounts=['kpay','wave','admin'];
        $account_name=['Mg Mg','Aung Aung','Admin'];
        $phone_number=['091111','092222','091234'];
        foreach($accounts as $k =>$account){
           $account= Account::create([
                'name'=>$account_name[$k],
                'phone_number'=>$phone_number[$k],
                'color_code'=>'#ffEEFF',
                'account_type'=>$accounts[$k],
                'deposit_id'=>1,
                'withdrawal_id'=>2,
                'is_active'=>1,
            ]);
        }
    }
}
