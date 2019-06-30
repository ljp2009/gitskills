<?php

use Illuminate\Database\Seeder;
use App\Models as MD;

class RoleTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Init roles data.');
        $this->initRoles();
        $this->command->info('Init roleskill data.');
        $this->initRoleSkill();
        $this->command->info('All  base data is ok!');
    }
    private function initRoles()
    {
        DB::table('t_ip_role')->truncate();
        //IP Sum Attributes
        for ($i = 1; $i < 5; ++$i) {
            $s = MD\IpRole::create(['ip_id' => '1',
            'name' => '千叶'.$i,
            'header' => 'hd'.$i.'.jpg',
            'intro' => '诛仙的主角张小凡'.$i.'，这里是他的介绍',
            'creator' => '1',
            'mender' => '1', ]);
        }
//         MD\IpContributor::create(['ip_id'=>'1', 'user_id'=>'1',
//             'type'=>'role','obj_id'=>$s->id]);
    }
    private function initRoleSkill()
    {
        DB::table('t_ip_role_skill')->truncate();
        //IP Sum Attributes
        MD\IpRoleSkill::create(['role_id' => '1',    'name' => '御剑', 'intro' => '御剑飞行', 'skill_type' => '3000101', 'header' => 'hd1.jpg']);
        MD\IpRoleSkill::create(['role_id' => '1',    'name' => '天书', 'intro' => '法术', 'skill_type' => '3000101', 'header' => 'hd2.jpg']);
        MD\IpRoleSkill::create(['role_id' => '1',    'name' => '太极玄清道', 'intro' => '青云门核心法术根基', 'skill_type' => '3000102', 'header' => 'hd3.jpg']);
    }
}
