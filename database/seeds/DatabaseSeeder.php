<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

//use App\Models as MD;

class DatabaseSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
   public function run()
   {
       Model::unguard();
       $this->call('PkTaskTestData');
//       $this->call('InitDataSeeder');
//       $this->call('InitIpSurveyDataSeeder');
       Model::reguard();
   }
}
