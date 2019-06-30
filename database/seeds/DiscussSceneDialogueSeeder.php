<?php

use Illuminate\Database\Seeder;
use App\Models\Discussion;
use App\Models\IpScene;
use App\Models\IpDialogue;

class DiscussSceneDialogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Create test data for Discussion');
        $this->createDiscusssion();
        $this->command->info('Create test data for Scene');
        $this->createScene();
        $this->command->info('Create test data for Dialogue');
        $this->createDialogue();
        $this->command->info('Succeed!!');
    }

    private function createDiscusssion()
    {
        $temp = '这是一个讨论的样式';
        DB::table('t_discussion')->delete();
        for ($i = 0; $i < 15; ++$i) {
            Discussion::create([
                'user_id' => 1,
                'resource' => 'ip',
                'resource_id' => 1,
                'text' => $temp.'(短评)',
                'type' => 0,
            ]);
        }
        for ($i = 0; $i < 15; ++$i) {
            Discussion::create([
                'user_id' => 1,
                'resource' => 'ip',
                'resource_id' => 1,
                'text' => $temp.'(长评)',
                'type' => 1,
            ]);
        }
    }

    private function createScene()
    {
        $temp = '这是一个经典场景样式';
        DB::table('t_ip_scene')->delete();
        for ($i = 0; $i < 15; ++$i) {
            IpScene::create([
                'user_id' => 1,
                'ip_id' => 1,
                'text' => $temp,
                'verified' => 1,
            ]);
        }
    }

    private function createDialogue()
    {
        DB::table('t_ip_dialogue')->delete();
        $temp = '这是一个经典场景样式';

        for ($i = 0; $i < 15; ++$i) {
            IpDialogue::create([
                'user_id' => 1,
                'ip_id' => 1,
                'text' => $temp,
                'verified' => 1,
            ]);
        }
    }
}
