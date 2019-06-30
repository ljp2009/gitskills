<?php

use App\Models as MD;
use Illuminate\Database\Seeder;

class ResHallTestDataSeeder extends Seeder
{
    private $seed;
    public function run()
    {
        if ($this->command->confirm('Do you need reset table data?')) {
            $this->clearAll();
            $this->createResHallBanner();
        }
    }
    private function clearAll()
    {
        /* dimension部分 */
        DB::table('t_hall_banner')->truncate();
    }
    private function createResHallBanner()
    {
        MD\HallBanner::create([
            'url' => '/ip/1',
            'description' => '第一个推荐内容',
            'image' => 'scene1.jpg',
        ]);
        MD\HallBanner::create([
            'url' => '/ip/2',
            'description' => '第二个推荐内容',
            'image' => 'scene1.jpg',
        ]);
        MD\HallBanner::create([
            'url' => '/ip/3',
            'description' => '第三个推荐内容',
            'image' => 'scene1.jpg',
        ]);
    }
}
