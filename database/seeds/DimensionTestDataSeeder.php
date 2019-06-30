<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models as MD;

class DimensionTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->command->info('Init dimension data.');
       $this->initDimension();
       $this->initDimensionAttr();
       $this->initDimensionEnter();
       $this->initDimensionLatelyUser();
       $this->initDimensionPublish();
       $this->initDimensionSum();
       $this->command->info('All  base data is ok!');
    }
    private function initDimension()
    {
    	DB::table('t_dimension')->truncate();
    	//IP Sum Attributes
    	for ($i=1; $i < 5; $i++) { 
             $s = MD\Dimension::create([
            'name'=>'千叶'.$i,
            'user_id'=>'1',
            'header'=>'hd'.$i.'.jpg',
            'cover'=>'hd'.$i.'.jpg',
            'text'=>'诛仙的主角张小凡'.$i]);
        }
//         MD\IpContributor::create(['ip_id'=>'1', 'user_id'=>'1', 
//             'type'=>'role','obj_id'=>$s->id]);
    }
    private function initDimensionAttr()
    {
    	DB::table('t_dimension_attr')->truncate();
    	//IP Sum Attributes
    	MD\DimensionAttr::create(['dimension_id'=>'1',    'code'=>'4000107', 'value'=>'人物']);
    	MD\DimensionAttr::create(['dimension_id'=>'1',    'code'=>'4000106', 'value'=>'法术']);
    	MD\DimensionAttr::create(['dimension_id'=>'1',    'code'=>'4000103', 'value'=>'小说']);
    }
    private function initDimensionEnter()
    {
    	DB::table('t_dimension_enter')->truncate();
    	//IP Sum Attributes
    	MD\DimensionEnter::create(['dimension_id'=>'1',    'user_id'=>'1', 'is_enter'=>'N']);
    	MD\DimensionEnter::create(['dimension_id'=>'2',    'user_id'=>'1', 'is_enter'=>'Y']);
    	MD\DimensionEnter::create(['dimension_id'=>'3',    'user_id'=>'1', 'is_enter'=>'N']);
    }
    private function initDimensionLatelyUser()
    {
    	DB::table('t_dimension_lately_user')->truncate();
    	//IP Sum Attributes
    	MD\DimensionLatelyUser::create(['dimension_id'=>'1',    'user_id'=>'1']);
    	MD\DimensionLatelyUser::create(['dimension_id'=>'2',    'user_id'=>'1']);
    	MD\DimensionLatelyUser::create(['dimension_id'=>'3',    'user_id'=>'1']);
    }
    private function initDimensionPublish()
    {
    	DB::table('t_dimension_lately_user')->truncate();
    	//IP Sum Attributes
    	MD\DimensionPublish::create(['dimension_id'=>'1',    'user_id'=>'1','image'=>'hd1.jpg;hd2.jpg;hd3.jpg;hd4.jpg;','text'=>'次元世界']);
    	MD\DimensionPublish::create(['dimension_id'=>'2',    'user_id'=>'1','image'=>'hd1.jpg','text'=>'次元世界1']);
    	MD\DimensionPublish::create(['dimension_id'=>'3',    'user_id'=>'1','text'=>'次元世界2']);
    }
    private function initDimensionSum()
    {
    	DB::table('t_dimension_lately_user')->truncate();
    	//IP Sum Attributes
    	MD\DimensionSum::create(['dimension_id'=>'1','code'=>'31001' ,   'value'=>'1']);
    	MD\DimensionSum::create(['dimension_id'=>'1', 'code'=>'31002' ,   'value'=>'0']);
    }
}