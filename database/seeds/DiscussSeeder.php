<?php

use Illuminate\Database\Seeder;
use App\Models\Discussion;
use App\Models\User;

class DiscussSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Create test data for Discussion');
       //$this->createUser();
       $this->createDiscusssion();
        $this->createReply();
        $this->command->info('Succeed!!');
    }

    private function createUser()
    {
        $user = User::create(['display_name' => '寂静鹰',
             'password' => bcrypt('111111'),
             'email' => 'silenceeagle@126.com',
             'mobile' => '15800769319',
             'wechat_token' => null,
             'avatar' => 'hd1.jpg',
             'status' => 'activated', ]);
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

    private function createReply()
    {
        $id = Discussion::first()->id;
        $temp = '这是一个回复的样式';
        $userid = 2;
        $username = '寂静鹰';
        $parentId = $id;
        for ($i = 0; $i < 15; ++$i) {
            if ($i % 2 == 0) {
                $userid = 2;
                $username = '寂静鹰';
            } else {
                $userid = 1;
                $username = '小寒';
            }
            $discussion = Discussion::create([
                'user_id' => $userid,
                'text' => $temp,
                'response_id' => $parentId,
                'reference_id' => $id,
            ]);
            $parentId = $discussion->id;
        }
    }
}
