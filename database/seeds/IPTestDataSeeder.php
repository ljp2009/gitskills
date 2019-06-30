<?php

use Illuminate\Database\Seeder;
use App\Models as MD;

class IPTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $seed = '';
    public function run()
    {
        if ($this->command->confirm('Do you need reset table index?')) {
            $this->clearAll();
        }
        for ($i = 0; $i < 3; ++$i) {
            $this->seed = 'test'.$i;
            $this->createAll();
        }
    }

    private function clearAll()
    {
        DB::table('t_user')->truncate();
        DB::table('t_ip')->truncate();
        DB::table('t_ip_attr')->truncate();
        DB::table('t_ip_tag')->truncate();
        DB::table('t_ip_sum')->truncate();
        DB::table('t_ip_intro')->truncate();
        DB::table('t_ip_contributor')->truncate();
        DB::table('t_ip_scene')->truncate();
        DB::table('t_ip_role')->truncate();
        DB::table('t_ip_recommend')->truncate();
        DB::table('t_image')->truncate();
        DB::table('t_ip_colleague')->truncate();
        DB::table('t_ip_peripheral')->truncate();
    }
    private function createAll()
    {
        $this->command->info('Add Test Data');
        $this->command->info('create user "小寒"');
        $uid = $this->createUsers();
        $this->command->info('create Ip "永夜君王"');
        $ipid = $this->createIp($uid);
        $this->command->info('create ip intro');
        $this->addintro($ipid, $uid);
        $this->command->info('create add ip roles');
        $this->addRoles($ipid, $uid);
        $this->command->info('create add ip Scenes and dialogue');
        $this->addScenes($ipid, $uid);
        $this->addDialogue($ipid, $uid);
        $this->command->info('create add ip recommends');
        $this->addRecommends($ipid, $uid);
        $this->command->info('create add ip attributes');
        $this->addAttrs($ipid, $uid);
        $this->command->info('create add ip sum attributes');
        $this->addSum($ipid, $uid);
        $this->command->info('create add ip tags');
        $this->addTags($ipid, $uid);
        $this->command->info('create add ip evaluates');
        $this->addEvaluates($ipid, $uid);
        $this->command->info('Add test data completed. name: "永夜君王" , id: "'.$ipid.'"');
        $this->addRelated($ipid, $uid);
        $this->command->info('add Ip Related');
    }
    private function createUsers()
    {
        $user = MD\User::create(['display_name' => '小寒'.$this->seed,
             'password' => bcrypt('111111'),
             'email' => 'hanxuetao'.$this->seed.'@viewstap.com',
             'mobile' => '13516148772',
             'wechat_token' => null,
             'avatar' => 'hd1.jpg',
             'status' => 'activated', ]);

        //用户头像
        MD\Image::create(['type' => 'avatar',
            'name' => 'hd1.jpg', 'obj_type' => 'user', 'obj_id' => $user->id, 'creator' => $user->id, ]);

        return $user->id;
    }
    private function createIp($userId)
    {
        $ip = MD\Ip::create(['name' => '永夜君王'.$this->seed, 'type' => 'story', 'cover' => 'cover1.jpg', 'creator' => $userId]);

        return $ip->id;
    }
    private function addRoles($ipId, $userId)
    {
        for ($i = 1; $i < 5; ++$i) {
            $s = MD\IpRole::create(['ip_id' => $ipId,
            'name' => '千叶'.$i,
            'header' => 'hd'.$i.'.jpg',
            'intro' => '永夜君王'.$this->seed.'的主角'.$i.'，这里是他的介绍',
            'creator' => $userId,
            'mender' => $userId, ]);
        }
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId,
            'type' => 'role', 'obj_id' => $s->id, ]);
    }
    private function addScenes($ipId, $userId)
    {
        for ($i = 0; $i < 10; ++$i) {
            $s = MD\IpScene::create([
                'ip_id' => $ipId,
                'text' => '这里是'.$this->seed.'第'.$i.'个场景的描述。场景描述的字数上没有太多的限制。',
                'image' => 'scene1.jpg',
                'user_id' => $userId,
                'verified' => 1,
                'verified_by' => $userId,
                'verified_at' => date('Y-m-d H:i:s'), ]);
            MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'scene', 'obj_id' => $s->id]);
        }
    }
    private function addDialogue($ipId, $userId)
    {
        for ($i = 0; $i < 10; ++$i) {
            $s = MD\IpDialogue::create([
                'ip_id' => $ipId,
                'text' => '这里是'.$this->seed.'第'.$i.'个场景的描述。场景描述的字数上没有太多的限制。',
                'user_id' => $userId,
                'verified' => 1,
                'verified_by' => $userId,
                'verified_at' => date('Y-m-d H:i:s'), ]);

            MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'dialogue', 'obj_id' => $s->id]);
        }
    }
    private function addTags($ipId, $userId)
    {
        $s = MD\IpTag::create(['ip_id' => $ipId, 'tag_id' => 1]);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'tag', 'obj_id' => $s->id]);
        $s = MD\IpTag::create(['ip_id' => $ipId, 'tag_id' => 2]);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'tag', 'obj_id' => $s->id]);
    }
    private function addAttrs($ipId, $userId)
    {
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10001', 'value' => '烟雨江南'.$this->seed]);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10002', 'value' => '2015/01/01'.$this->seed]);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10005', 'value' => '连载中'.$this->seed]);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10004', 'value' => '起点中文网'.$this->seed]);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'attr', 'obj_id' => $ipId]);
    }
    private function addSum($ipId)
    {
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11001', 'value' => '1']);
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11002', 'value' => '2']);
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11003', 'value' => '5']);
    }
    private function addRecommends($ipId, $userId)
    {
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '1']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '2']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '3']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '4']);
    }
    private function addIntro($ipId, $userId)
    {
    }
    private function addEvaluates($ipId, $userId)
    {
        MD\IpEvaluate::create(['ip_id' => $ipId, 'user_id' => $userId, 'grade' => '3']);
    }
    private function addRelated($ipId, $userId)
    {
        for ($i = 0; $i < 20; ++$i) {
            MD\IpColleague::create([
                'ip_id' => $ipId,
                'user_id' => $userId,
                'title' => $this->seed.'同人'.$i,
                'cover' => 'cover'.($i % 5 + 1).'.jpg',
                'text' => '这里是是关于同人作品《'.$this->seed.'同人'.$i.'》的详细描述,这个描述没有特别多的限制',
            ]);
            MD\IpPeripheral::create([
                'ip_id' => $ipId,
                'user_id' => $userId,
                'title' => $this->seed.'周边产品'.$i,
                'image' => 'scene'.($i % 4 + 1).'.jpg',
                'text' => '这里是是关于【《'.$this->seed.'周边产品'.$i.'》】的详细描述,这个描述没有特别多的限制',
            ]);
        }
    }
}
