<?php

use App\Models as MD;
use Illuminate\Database\Seeder;

class UserTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $seed;
    public function run()
    {
        if ($this->command->confirm('Do you need reset table 测试?')) {
            $this->clearAll();
        }
        for ($i = 0; $i < 3; ++$i) {
            $this->seed = 'test'.$i;
            $this->createAll();
        }
    }
    private function clearAll()
    {
        /* dimension部分 */
        DB::table('t_dimension')->truncate();
        DB::table('t_dimension_attr')->truncate();
        DB::table('t_dimension_enter')->truncate();
        DB::table('t_dimension_lately_user')->truncate();
        DB::table('t_dimension_publish')->truncate();
        DB::table('t_dimension_sum')->truncate();
        /* user部分 */
        DB::table('t_user')->truncate();
        DB::table('t_user_attr')->truncate();
        DB::table('t_user_sum')->truncate();
        DB::table('t_user_badge')->truncate();
        DB::table('t_user_production')->truncate();
        DB::table('t_user_detail_status')->truncate();
        DB::table('t_user_relation')->truncate();
        DB::table('t_user_gold_record')->truncate();
        DB::table('t_user_private_letter')->truncate();
        DB::table('t_user_skill')->truncate();
        /* 讨论、喜欢、评分部分 */
        DB::table('t_discussion')->truncate();
        DB::table('t_like')->truncate();
        DB::table('t_like_sum')->truncate();
        DB::table('t_score')->truncate();
        DB::table('t_score_sum')->truncate();
        /* ip部分 */
        DB::table('t_ip')->truncate();
        DB::table('t_ip_attr')->truncate();
        DB::table('t_ip_colleague')->truncate();
        DB::table('t_ip_contributor')->truncate();
        DB::table('t_ip_dialogue')->truncate();

        DB::table('t_ip_evaluate')->truncate();
        DB::table('t_ip_intro')->truncate();
        DB::table('t_ip_peripheral')->truncate();
        DB::table('t_ip_recommend')->truncate();

        DB::table('t_ip_role')->truncate();
        DB::table('t_ip_role_skill')->truncate();
        DB::table('t_ip_scene')->truncate();
        DB::table('t_ip_sum')->truncate();
        DB::table('t_ip_tag')->truncate();
        DB::table('t_ip_user_status')->truncate();
    }
    private function initUser()
    {
        $user = MD\User::create([
            'password' => '$2y$10$qL3xB9q6L4n4SnZoNx7fM.tWbZds.syr7q1ELxL.oFyWr4IiTl4UK',
            'display_name' => '小崔'.$this->seed,
            'email' => '529975389@qq.com',
            'mobile' => '15879698998',
            'avatar' => 'hd1.jpg',
            'background' => 'user-bg.jpg',
            'status' => 'activated', ]);

        return $user->id;
    }
    private function createAll()
    {
        $uid = $this->initUser();
        /* 用户部分测试数据  */
        $this->initUserAttr($uid);
        $this->initUserBadge($uid);
        $this->initUserSum($uid);
        $this->initUserProduction($uid);
        $this->initUserDetailStatus($uid);
        $this->initUserPrivateLetter($uid);
        $this->initUserSkill($uid);
        /* 评论测试数据 */
        $this->createDiscusssion($uid);
        $this->createReply();
        /* 二次元测试数据  */
        $dimensionID = $this->initDimension($uid);
        $this->initDimensionAttr($dimensionID);
        $this->initDimensionEnter($dimensionID, $uid);
        $this->initDimensionPublish($dimensionID, $uid);
        $this->initDimensionSum($dimensionID);
        /* ip测试数据   */
        $ipid = $this->createIp($uid);
        $this->addAttrs($ipid, $uid);
        $this->addRelated($ipid, $uid);
        $this->addDialogue($ipid, $uid);
        $this->addEvaluates($ipid, $uid);
        $this->addIntro($ipid);
        $this->addRecommends($ipid, $uid);
        $roleid = $this->initRoles($ipid, $uid);
        $this->initRoleSkill($roleid);

        $this->addScenes($ipid, $uid);
        $this->addSum($ipid);
        $this->addTags($ipid, $uid);
        $this->initIpUserStatus($ipid, $uid);
        $this->initLike($uid);
        $this->command->info('All test data is ok!');
    }

    private function initUserAttr($uid)
    {
        //DB::table('t_user_attr')->delete();
        //IP Sum Attributes
        // for($i=0;$i<=10;$i++){
        $i = $uid;
        if ($i % 2 == 0) {
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20002', 'attr_value' => '男']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20003', 'attr_value' => '已婚']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20011', 'attr_value' => '22']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20006', 'attr_value' => '这里是签名档签名档，假字不在回家的路上就在上班的路上。']);
        } else {
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20002', 'attr_value' => '女']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20003', 'attr_value' => '未婚']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20011', 'attr_value' => '21']);
            MD\UserAttr::create(['user_id' => $i, 'attr_code' => '20006', 'attr_value' => '这里是签名档签名档，假字不在回家的路上就在上班的路上222。']);
        }
        // }
    }

    private function initUserSum($uid)
    {
        //DB::table('t_user_sum')->delete();
        //IP Sum Attributes
        // for($i=1;$i<=10;$i++){
        MD\UserSum::create(['user_id' => $uid, 'sum_code' => '21001', 'value' => '59']);
        MD\UserSum::create(['user_id' => $uid, 'sum_code' => '21002', 'value' => '29']);
        // }
    }

    private function initUserBadge($uid)
    {
        MD\UserBadge::create(['user_id' => $uid, 'badge_id' => '1']);
        MD\UserBadge::create(['user_id' => $uid, 'badge_id' => '2']);
    }

    private function initUserProduction($uid)
    {
        //  DB::table('t_user_production')->delete();
        //IP Sum Attributes
        $j = 0;
        for ($i = 0; $i <= 6; ++$i) {
            if ($i > 0) {
                ++$j;
                if ($j > 4) {
                    $j = 1;
                }
            } else {
                $j = 1;
            }
            MD\UserProduction::create([
                'user_id' => $uid,
                'name' => '小画家'.$i,
                'image' => 'hd'.$j.'.jpg',
                'is_original' => '1',
                'is_sell' => '1',
                'intro' => '小画家介绍'.$i,
                'price' => '155',
                'attr_code' => '2001203',
                'sell_intro' => '不接受砍价',
                'is_deleted' => '1', ]);
        }
    }

    private function initUserDetailStatus($uid)
    {
        //  DB::table('t_user_detail_status')->delete();
        //IP Sum Attributes
        // for($i=1;$i<=10;$i++){
        if ($uid % 2 == 0) {
            MD\UserDetailStatus::create([
                'user_id' => $uid,
                'score' => '20',
                'gold' => '2000',
                'level' => '1',
                'character' => '2000801',
                'position' => '2000902',
                'behavior' => '2000702',
                'is_expert' => '1', ]);
        } else {
            MD\UserDetailStatus::create([
                'user_id' => $uid,
                'score' => '20',
                'gold' => '3000',
                'level' => '1',
                'character' => '2000802',
                'position' => '2000901',
                'behavior' => '2000701',
                'is_expert' => '1', ]);
        }
        // }
    }

    private function initLike($uid)
    {
        //  DB::table('t_like')->delete();
        //IP Sum Attributes
        MD\LikeModel::create(['user_id' => $uid, 'resource' => 'ip', 'resource_id' => '1']);
        MD\LikeModel::create(['user_id' => $uid, 'resource' => 'ip', 'resource_id' => '2']);
        MD\LikeModel::create(['user_id' => $uid, 'resource' => 'ip', 'resource_id' => '3']);
        // MD\LikeModel::create(['user_id'=>'2','resource'=>'ip','resource_id'=>'1']);
        // MD\LikeModel::create(['user_id'=>'3','resource'=>'ip','resource_id'=>'2']);
    }

    private function initIpUserStatus($ipid, $uid)
    {
        MD\IpUserStatus::create(['user_id' => $uid, 'ip_id' => $ipid, 'status' => 'reading']);
    }

    private function initUserPrivateLetter($uid)
    {
        if ($uid < 3) {
            MD\UserPrivateLetter::create([
                'user_id' => $uid,
                'send_id' => $uid + 1,
                'status' => 'N',
                'msg' => '你的头像很好看，你的头像很好看，你的头像很好看，你的头像很好看，你的头像很好看，你的头像很好看，'.$uid, ]);
        }
    }

    private function createDiscusssion($uid)
    {
        $temp = '这是一个讨论的样式';
        for ($i = 0; $i < 15; ++$i) {
            MD\Discussion::create([
                'user_id' => $uid,
                'resource' => 'ip',
                'resource_id' => 1,
                'text' => $temp.'(短评)',
                'type' => 0,
            ]);
        }
        for ($i = 0; $i < 15; ++$i) {
            MD\Discussion::create([
                'user_id' => $uid,
                'resource' => 'ip',
                'resource_id' => 1,
                'text' => $temp.'(长评)',
                'type' => 1,
            ]);
        }
    }

    private function createReply()
    {
        $id = MD\Discussion::first()->id;
        $temp = '这是一个回复的样式';
        $userid = 2;
        $parentId = $id;
        for ($i = 0; $i < 15; ++$i) {
            if ($i % 2 == 0) {
                $userid = 2;
            } else {
                $userid = 1;
            }
            $discussion = MD\Discussion::create([
                'user_id' => $userid,
                'text' => $temp,
                'response_id' => $parentId,
                'reference_id' => $id,
            ]);
            $parentId = $discussion->id;
        }
    }

    /* 二次元 */
    private function initDimension($uid)
    {
        $s = MD\Dimension::create([
            'name' => '千叶'.$uid,
            'user_id' => $uid,
            'header' => 'hd'.$uid.'.jpg',
            'cover' => 'hd'.$uid.'.jpg',
            'text' => '诛仙的主角张小凡'.$uid, ]);

        return $s->id;
    }
    private function initDimensionAttr($id)
    {
        MD\DimensionAttr::create(['dimension_id' => $id, 'code' => '4000107', 'value' => '人物']);
        MD\DimensionAttr::create(['dimension_id' => $id, 'code' => '4000106', 'value' => '法术']);
        MD\DimensionAttr::create(['dimension_id' => $id, 'code' => '4000103', 'value' => '小说']);
    }
    private function initDimensionEnter($id, $uid)
    {
        MD\DimensionEnter::create(['dimension_id' => $id, 'user_id' => $uid, 'is_enter' => 'Y']);
    }
    private function initDimensionPublish($id, $uid)
    {
        MD\DimensionPublish::create([
            'dimension_id' => $id,
            'user_id' => $uid,
            'image' => 'hd1.jpg;hd2.jpg;hd3.jpg;hd4.jpg;',
            'text' => '次元世界'.$uid, ]);
    }
    private function initDimensionSum($id)
    {
        MD\DimensionSum::create(['dimension_id' => $id, 'code' => '31001', 'value' => '1']);
        MD\DimensionSum::create(['dimension_id' => $id, 'code' => '31002', 'value' => '0']);
    }
    /* ip部分测试数据  */
    private function createIp($userId)
    {
        $ip = MD\Ip::create(['name' => '永夜君王'.$this->seed, 'type' => 'story', 'cover' => 'cover1.jpg', 'creator' => $userId]);

        return $ip->id;
    }
    private function addAttrs($ipId, $userId)
    {
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10008', 'value' => '烟雨江南']);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10010', 'value' => '2015/01/01']);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10009', 'value' => '连载中']);
        MD\IpAttr::create(['ip_id' => $ipId, 'code' => '10011', 'value' => '300万字']);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'attr', 'obj_id' => $ipId]);
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
    private function addEvaluates($ipId, $userId)
    {
        MD\IpEvaluate::create(['ip_id' => $ipId, 'user_id' => $userId, 'grade' => '3']);
    }

    private function addIntro($ipId)
    {
        MD\IpIntro::create(['ip_id' => $ipId, 'intro' => '萧鼎经典。。。。'.$ipId]);
    }

    private function addRecommends($ipId, $userId)
    {
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '1']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '2']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '3']);
        MD\IpRecommend::create(['ip_id' => $ipId, 'user_id' => $userId, 'recom_type' => '4']);
    }
    private function initRoles($ipid, $uid)
    {
        $s = MD\IpRole::create([
            'ip_id' => $ipid,
            'name' => '千叶'.$ipid,
            'header' => 'hd'.$ipid.'.jpg',
            'intro' => '诛仙的主角张小凡'.$ipid.'，这里是他的介绍',
            'creator' => $uid,
            'mender' => $uid, ]);

        return $s->id;
    }
    private function initRoleSkill($roleID)
    {
        MD\IpRoleSkill::create(['role_id' => $roleID, 'name' => '御剑', 'intro' => '御剑飞行', 'skill_type' => '3000101', 'header' => 'hd1.jpg']);
        MD\IpRoleSkill::create(['role_id' => $roleID, 'name' => '天书', 'intro' => '法术', 'skill_type' => '3000101', 'header' => 'hd2.jpg']);
        MD\IpRoleSkill::create(['role_id' => $roleID, 'name' => '太极玄清道', 'intro' => '青云门核心法术根基', 'skill_type' => '3000102', 'header' => 'hd3.jpg']);
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
    private function addSum($ipId)
    {
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11001', 'value' => '1']);
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11002', 'value' => '2']);
        MD\IpSum::create(['ip_id' => $ipId, 'code' => '11003', 'value' => '5']);
    }
    private function addTags($ipId, $userId)
    {
        $s = MD\IpTag::create(['ip_id' => $ipId, 'tag_id' => 1]);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'tag', 'obj_id' => $s->id]);
        $s = MD\IpTag::create(['ip_id' => $ipId, 'tag_id' => 2]);
        MD\IpContributor::create(['ip_id' => $ipId, 'user_id' => $userId, 'type' => 'tag', 'obj_id' => $s->id]);
    }
    private function initUserSkill($uid)
    {
        MD\UserSkill::create(['user_id' => $uid, 'code' => '2001002', 'level' => '1']);
        MD\UserSkill::create(['user_id' => $uid, 'code' => '2001004', 'level' => '2']);
    }
}
