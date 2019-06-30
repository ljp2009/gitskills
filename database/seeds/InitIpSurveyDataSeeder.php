<?php

use App\Models as MD;
use Illuminate\Database\Seeder;

class InitIpSurveyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Begin Init base data.');
        $this->initSysIpSurvey();
        $this->command->info('All  base data is ok!');
    }

    private function initSysIpSurvey()
    {
        DB::table('sys_ip_survey')->truncate();
        $type = '';
        for ($i = 1; $i <= 27; ++$i) {
            if ($i <= 9) {
                $type = 'cartoon';
            } elseif ($i >= 10 && $i <= 18) {
                $type = 'story';
            } elseif ($i >= 19 && $i <= 27) {
                $type = 'game';
            }
            MD\SysIpSurvey::create([
            'type' => $type,
            'image' => 'scene1.jpg',
            'name' => '作品'.$i,
            'attrs' => json_encode(array('5000101' => '5', '5000102' => '3')), ]);
        }
    }
}
