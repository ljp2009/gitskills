<?php

use App\Models as MD;
use Illuminate\Database\Seeder;

class InitDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Begin Init base data.');

        $this->initAttr();
        $this->initSum();
        $this->initEnum();
        $this->initTags();
        $this->initSysBadge();
        $this->initSysTaskTag();
        $this->initSysUserSkill();
        $this->initUser();
//         $this->initSysIpSurvey();
        $this->command->info('All  base data is ok!');
    }

    private function initAttr()
    {
        $this->command->info('Init attributes');
        DB::table('sys_attr')->delete();
        MD\SysAttr::create(['name' => '标签', 'code' => '10099', 'depend' => 'ip', 'data_type' => 'label', 'sort' => '99', 'display' => '{value}']);
          //IP Attributes
         MD\SysAttr::create(['name' => '作画监督', 'code' => '10001', 'depend' => 'cartoon', 'data_type' => 'label', 'sort' => '1', 'display' => '作画监督：{value}']);
        MD\SysAttr::create(['name' => '作品状态', 'code' => '10002', 'depend' => 'cartoon', 'data_type' => 'enum', 'sort' => '2', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '首播时间', 'code' => '10003', 'depend' => 'cartoon', 'data_type' => 'date', 'sort' => '3', 'display' => '首播：{value}']);
        MD\SysAttr::create(['name' => '集数', 'code' => '10004', 'depend' => 'cartoon', 'data_type' => 'number', 'sort' => '4', 'display' => '共{value}集']);

        MD\SysAttr::create(['name' => '开发商', 'code' => '10005', 'depend' => 'game', 'data_type' => 'label', 'sort' => '5', 'display' => '开发商：{value}']);
        MD\SysAttr::create(['name' => '发行日期', 'code' => '10006', 'depend' => 'game', 'data_type' => 'date', 'sort' => '6', 'display' => '{value}发行']);
        MD\SysAttr::create(['name' => '游戏类型', 'code' => '10007', 'depend' => 'game', 'data_type' => 'label', 'sort' => '7', 'display' => '游戏类型：{value}']);

        MD\SysAttr::create(['name' => '作者', 'code' => '10008', 'depend' => 'story', 'data_type' => 'label', 'sort' => '8', 'display' => '作者：{value}']);
        MD\SysAttr::create(['name' => '作品状态', 'code' => '10009', 'depend' => 'story', 'data_type' => 'enum', 'sort' => '9', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '首发时间', 'code' => '10010', 'depend' => 'story', 'data_type' => 'date', 'sort' => '10', 'display' => '首发：{value}']);
        MD\SysAttr::create(['name' => '字数', 'code' => '10011', 'depend' => 'story', 'data_type' => 'label', 'sort' => '11', 'display' => '{value}']);

         //User Attributes
         MD\SysAttr::create(['name' => '生日', 'code' => '20001', 'depend' => 'user', 'data_type' => 'label', 'sort' => '1', 'display' => ' {value}']);
        MD\SysAttr::create(['name' => '性别', 'code' => '20002', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '2', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '婚姻状况', 'code' => '20003', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '3', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '学历', 'code' => '20004', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '4', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '职业类型', 'code' => '20005', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '5', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '签名档', 'code' => '20006', 'depend' => 'user', 'data_type' => 'label', 'sort' => '6', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '行为', 'code' => '20007', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '7', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '性格', 'code' => '20008', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '8', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '地位', 'code' => '20009', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '9', 'display' => '{value}']);

        MD\SysAttr::create(['name' => '年龄', 'code' => '20011', 'depend' => 'user', 'data_type' => 'number', 'sort' => '11', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '产品标签', 'code' => '20012', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '12', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '技能等级', 'code' => '20013', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '13', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '信誉等级', 'code' => '20014', 'depend' => 'user', 'data_type' => 'enum', 'sort' => '14', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '城市', 'code' => '20015', 'depend' => 'user', 'data_type' => 'number', 'sort' => '15', 'display' => '{value}']);
        //role Attributes
         MD\SysAttr::create(['name' => '角色技能', 'code' => '30001', 'depend' => 'role', 'data_type' => 'enum', 'sort' => '1', 'display' => '{value}']);

         //dimension Attributes
         MD\SysAttr::create(['name' => '次元标签', 'code' => '40001', 'depend' => 'dimension', 'data_type' => 'enum', 'sort' => '1', 'display' => '{value}']);
        MD\SysAttr::create(['name' => '次元属性', 'code' => '40002', 'depend' => 'dimension', 'data_type' => 'enum', 'sort' => '2', 'display' => '{value}']);
    }
    private function initSum()
    {
        $this->command->info('Init sum attributes.');
        DB::table('sys_sum_attr')->truncate();
        //IP Sum Attributes
        MD\SysSumAttr::create(['name' => '贡献者数量', 'code' => '11001', 'depend' => 'ip', 'sort' => '1']);
        MD\SysSumAttr::create(['name' => '推荐次数', 'code' => '11002', 'depend' => 'ip', 'sort' => '2']);
        MD\SysSumAttr::create(['name' => '达人喜欢次数', 'code' => '11003', 'depend' => 'ip', 'sort' => '3']);

        //User Sum  Attributes
        MD\SysSumAttr::create(['name' => '粉丝数量', 'code' => '21001', 'depend' => 'user', 'sort' => '1']);
        MD\SysSumAttr::create(['name' => '关注数', 'code' => '21002', 'depend' => 'user', 'sort' => '2']);

        //dimension Sum Attributes
        MD\SysSumAttr::create(['name' => '入驻', 'code' => '31001', 'depend' => 'dimension', 'sort' => '1']);
        MD\SysSumAttr::create(['name' => '发布', 'code' => '31002', 'depend' => 'dimension', 'sort' => '2']);
    }
    private function initEnum()
    {
        $this->command->info('Init attributes enumeration.');
        DB::table('sys_attr_enum')->truncate();

        MD\SysAttrEnum::create(['name' => '男', 'table_name' => 'sys_attr', 'column' => '20002', 'code' => '2000201']);
        MD\SysAttrEnum::create(['name' => '女', 'table_name' => 'sys_attr', 'column' => '20002', 'code' => '2000202']);
        MD\SysAttrEnum::create(['name' => '未婚', 'table_name' => 'sys_attr', 'column' => '20003', 'code' => '2000301']);
        MD\SysAttrEnum::create(['name' => '已婚', 'table_name' => 'sys_attr', 'column' => '20003', 'code' => '2000302']);
        MD\SysAttrEnum::create(['name' => '离异', 'table_name' => 'sys_attr', 'column' => '20003', 'code' => '2000303']);
        MD\SysAttrEnum::create(['name' => '小学', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000401']);
        MD\SysAttrEnum::create(['name' => '初中', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000402']);
        MD\SysAttrEnum::create(['name' => '中专', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000403']);
        MD\SysAttrEnum::create(['name' => '高中', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000404']);
        MD\SysAttrEnum::create(['name' => '大专', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000405']);
        MD\SysAttrEnum::create(['name' => '本科', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000406']);
        MD\SysAttrEnum::create(['name' => '硕士', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000407']);
        MD\SysAttrEnum::create(['name' => '博士', 'table_name' => 'sys_attr', 'column' => '20004', 'code' => '2000408']);
        MD\SysAttrEnum::create(['name' => '学生', 'table_name' => 'sys_attr', 'column' => '20005', 'code' => '2000501']);
        MD\SysAttrEnum::create(['name' => '企业职员', 'table_name' => 'sys_attr', 'column' => '20005', 'code' => '2000502']);
        MD\SysAttrEnum::create(['name' => '自由职业者', 'table_name' => 'sys_attr', 'column' => '20005', 'code' => '2000503']);
        MD\SysAttrEnum::create(['name' => '个体经营者', 'table_name' => 'sys_attr', 'column' => '20005', 'code' => '2000504']);
        MD\SysAttrEnum::create(['name' => '公职人员', 'table_name' => 'sys_attr', 'column' => '20005', 'code' => '2000505']);
        MD\SysAttrEnum::create(['name' => '特立独行的', 'table_name' => 'sys_attr', 'column' => '20007', 'code' => '2000701']);
        MD\SysAttrEnum::create(['name' => '长袖善舞的', 'table_name' => 'sys_attr', 'column' => '20007', 'code' => '2000702']);
        MD\SysAttrEnum::create(['name' => '知根知底的', 'table_name' => 'sys_attr', 'column' => '20007', 'code' => '2000703']);
        MD\SysAttrEnum::create(['name' => '受人爱戴的', 'table_name' => 'sys_attr', 'column' => '20007', 'code' => '2000704']);
        MD\SysAttrEnum::create(['name' => '狮心', 'table_name' => 'sys_attr', 'column' => '20008', 'code' => '2000801']);
        MD\SysAttrEnum::create(['name' => '智慧', 'table_name' => 'sys_attr', 'column' => '20008', 'code' => '2000802']);
        MD\SysAttrEnum::create(['name' => '果敢', 'table_name' => 'sys_attr', 'column' => '20008', 'code' => '2000803']);
        MD\SysAttrEnum::create(['name' => '魅力', 'table_name' => 'sys_attr', 'column' => '20008', 'code' => '2000804']);
        MD\SysAttrEnum::create(['name' => '亲切', 'table_name' => 'sys_attr', 'column' => '20008', 'code' => '2000805']);
        MD\SysAttrEnum::create(['name' => '神爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000901']);
        MD\SysAttrEnum::create(['name' => '王爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000902']);
        MD\SysAttrEnum::create(['name' => '公爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000903']);
        MD\SysAttrEnum::create(['name' => '侯爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000904']);
        MD\SysAttrEnum::create(['name' => '伯爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000905']);
        MD\SysAttrEnum::create(['name' => '子爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000906']);
        MD\SysAttrEnum::create(['name' => '男爵', 'table_name' => 'sys_attr', 'column' => '20009', 'code' => '2000907']);
        MD\SysAttrEnum::create(['name' => '文案', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001001']);
        MD\SysAttrEnum::create(['name' => '编剧', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001002']);
        MD\SysAttrEnum::create(['name' => '音乐', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001003']);
        MD\SysAttrEnum::create(['name' => '配音', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001004']);
        MD\SysAttrEnum::create(['name' => '画师', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001005']);
        MD\SysAttrEnum::create(['name' => '设计', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001006']);
        MD\SysAttrEnum::create(['name' => '剪辑', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001007']);
        MD\SysAttrEnum::create(['name' => 'Coser', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001008']);
        MD\SysAttrEnum::create(['name' => '摄影', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001009']);
        MD\SysAttrEnum::create(['name' => '化妆', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001010']);
        MD\SysAttrEnum::create(['name' => '开发', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001011']);
        MD\SysAttrEnum::create(['name' => '策划', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001012']);
        MD\SysAttrEnum::create(['name' => 'PM', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001013']);
        MD\SysAttrEnum::create(['name' => '资料', 'table_name' => 'sys_attr', 'column' => '20010', 'code' => '2001014']);

        MD\SysAttrEnum::create(['name' => '文案', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001201']);
        MD\SysAttrEnum::create(['name' => '编剧', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001202']);
        MD\SysAttrEnum::create(['name' => '音乐', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001203']);
        MD\SysAttrEnum::create(['name' => '配音', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001204']);
        MD\SysAttrEnum::create(['name' => '画师', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001205']);
        MD\SysAttrEnum::create(['name' => '设计', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001206']);
        MD\SysAttrEnum::create(['name' => '剪辑', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001207']);
        MD\SysAttrEnum::create(['name' => '模特', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001208']);
        MD\SysAttrEnum::create(['name' => '摄影', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001209']);
        MD\SysAttrEnum::create(['name' => '化妆', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001210']);
        MD\SysAttrEnum::create(['name' => '开发', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001211']);
        MD\SysAttrEnum::create(['name' => '策划', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001212']);
        MD\SysAttrEnum::create(['name' => 'PM', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001213']);
        MD\SysAttrEnum::create(['name' => '资料', 'table_name' => 'sys_attr', 'column' => '20012', 'code' => '2001214']);

        //角色技能枚举
        MD\SysAttrEnum::create(['name' => '单体伤害', 'table_name' => 'sys_attr', 'column' => '30001', 'code' => '3000101']);
        MD\SysAttrEnum::create(['name' => '全体伤害', 'table_name' => 'sys_attr', 'column' => '30001', 'code' => '3000102']);
        MD\SysAttrEnum::create(['name' => '单体控制', 'table_name' => 'sys_attr', 'column' => '30001', 'code' => '3000103']);
        MD\SysAttrEnum::create(['name' => '全体控制', 'table_name' => 'sys_attr', 'column' => '30001', 'code' => '3000104']);
        //次元
        MD\SysAttrEnum::create(['name' => '动漫', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000101']);
        MD\SysAttrEnum::create(['name' => '游戏', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000102']);
        MD\SysAttrEnum::create(['name' => '小说', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000103']);
        MD\SysAttrEnum::create(['name' => 'Cos', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000104']);
        MD\SysAttrEnum::create(['name' => '抱图', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000105']);
        MD\SysAttrEnum::create(['name' => '人物', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000106']);
        MD\SysAttrEnum::create(['name' => '兴趣', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000107']);
        MD\SysAttrEnum::create(['name' => '展会', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000108']);
        MD\SysAttrEnum::create(['name' => '生活', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000109']);
        MD\SysAttrEnum::create(['name' => '地区', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000110']);
        MD\SysAttrEnum::create(['name' => '社团', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000111']);
        MD\SysAttrEnum::create(['name' => '逗比', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000112']);
        MD\SysAttrEnum::create(['name' => '其它', 'table_name' => 'sys_attr', 'column' => '40001', 'code' => '4000113']);

        MD\SysAttrEnum::create(['name' => '萌', 'table_name' => 'sys_attr', 'column' => '40002', 'code' => '4000201']);
        MD\SysAttrEnum::create(['name' => '宅', 'table_name' => 'sys_attr', 'column' => '40002', 'code' => '4000202']);
    }
    private function initTags()
    {
        $this->command->info('Init Tags.');
        DB::table('sys_tag')->truncate();

        MD\SysTag::create(['name' => '轻小说', 'code' => '10001', 'hot' => 1, 'depend' => 'story']);
        MD\SysTag::create(['name' => '网络小说', 'code' => '10002', 'hot' => 2, 'depend' => 'story']);
        MD\SysTag::create(['name' => '游戏竞技', 'code' => '10003', 'hot' => 3, 'depend' => 'story']);

        MD\SysTag::create(['name' => '泡面番', 'code' => '10004', 'hot' => 1, 'depend' => 'cartoon']);
        MD\SysTag::create(['name' => '游戏竞技', 'code' => '10005', 'hot' => 2, 'depend' => 'cartoon']);

        MD\SysTag::create(['name' => '换装游戏', 'code' => '10006', 'hot' => 1, 'depend' => 'game']);
        MD\SysTag::create(['name' => '休闲游戏', 'code' => '10007', 'hot' => 2, 'depend' => 'game']);
        MD\SysTag::create(['name' => 'ADV', 'code' => '10008', 'hot' => 3, 'depend' => 'game']);
        MD\SysTag::create(['name' => '音乐游戏', 'code' => '10009', 'hot' => 4, 'depend' => 'game']);
        MD\SysTag::create(['name' => '角色扮演', 'code' => '10010', 'hot' => 5, 'depend' => 'game']);
        MD\SysTag::create(['name' => 'ACT', 'code' => '10011', 'hot' => 6, 'depend' => 'game']);

        MD\SysTag::create(['name' => '回合制', 'code' => '10012', 'hot' => 7, 'depend' => 'game']);
        MD\SysTag::create(['name' => '卡牌', 'code' => '10013', 'hot' => 8, 'depend' => 'game']);
        MD\SysTag::create(['name' => 'MOBA', 'code' => '10014', 'hot' => 9, 'depend' => 'game']);
        MD\SysTag::create(['name' => '即时策略', 'code' => '10015', 'hot' => 10, 'depend' => 'game']);
        MD\SysTag::create(['name' => '射击', 'code' => '10016', 'hot' => 11, 'depend' => 'game']);
        MD\SysTag::create(['name' => '手游', 'code' => '10017', 'hot' => 12, 'depend' => 'game']);
        MD\SysTag::create(['name' => '端游', 'code' => '10018', 'hot' => 13, 'depend' => 'game']);
        MD\SysTag::create(['name' => '单机游戏', 'code' => '10019', 'hot' => 14, 'depend' => 'game']);
        MD\SysTag::create(['name' => '竞技体育', 'code' => '10020', 'hot' => 15, 'depend' => 'game']);

        MD\SysTag::create(['name' => '刑侦推理', 'code' => '10021', 'hot' => 21, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '后宫', 'code' => '10022', 'hot' => 22, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '无CP', 'code' => '10023', 'hot' => 23, 'depend' => 'ip']);
        MD\SysTag::create(['name' => 'BG', 'code' => '10024', 'hot' => 24, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '百合', 'code' => '10025', 'hot' => 25, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '耽美', 'code' => '10026', 'hot' => 26, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '温馨', 'code' => '10027', 'hot' => 27, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '猎奇', 'code' => '10028', 'hot' => 28, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '虐心', 'code' => '10029', 'hot' => 29, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '严肃', 'code' => '10030', 'hot' => 30, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '搞笑', 'code' => '10031', 'hot' => 31, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '轻松', 'code' => '10032', 'hot' => 32, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '养成', 'code' => '10033', 'hot' => 33, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '魔法魔幻', 'code' => '10034', 'hot' => 34, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '商战风云', 'code' => '10035', 'hot' => 35, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '官场沉浮', 'code' => '10036', 'hot' => 36, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '异术超能', 'code' => '10037', 'hot' => 37, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '娱乐圈', 'code' => '10038', 'hot' => 38, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '重生', 'code' => '10039', 'hot' => 39, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '黑道', 'code' => '10040', 'hot' => 40, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '都市生活', 'code' => '10041', 'hot' => 41, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '少年热血', 'code' => '10042', 'hot' => 42, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '古典仙侠', 'code' => '10043', 'hot' => 43, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '古典神话', 'code' => '10044', 'hot' => 44, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '修真', 'code' => '10045', 'hot' => 45, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '青春', 'code' => '10046', 'hot' => 46, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '战争', 'code' => '10047', 'hot' => 47, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '科幻', 'code' => '10048', 'hot' => 48, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '悬疑恐怖', 'code' => '10049', 'hot' => 49, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '星际战争', 'code' => '10050', 'hot' => 50, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '异世大陆', 'code' => '10051', 'hot' => 51, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '玄幻奇幻', 'code' => '10052', 'hot' => 52, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '武侠', 'code' => '10053', 'hot' => 53, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '治愈系', 'code' => '10054', 'hot' => 54, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '残念系', 'code' => '10055', 'hot' => 55, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '黑暗系', 'code' => '10056', 'hot' => 56, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '冒险', 'code' => '10057', 'hot' => 57, 'depend' => 'ip']);
        MD\SysTag::create(['name' => '爱情', 'code' => '10058', 'hot' => 58, 'depend' => 'ip']);
    }

    private function initSysBadge()
    {
        //    DB::table('sys_badge')->delete();
        //IP Sum Attributes
        $this->command->info('Init sys badge.');
        DB::table('sys_badge')->truncate();
        MD\SysBadge::create(['type' => 'badge', 'badge' => 'it.png']);
        MD\SysBadge::create(['type' => 'badge', 'badge' => 's6.png']);
        MD\SysBadge::create(['type' => 'badge', 'badge' => 'v.png']);
        MD\SysBadge::create(['type' => 'badge', 'badge' => 'we.png']);
        MD\SysBadge::create(['type' => 'badge', 'badge' => 'youtube.png']);
    }
    private function initSysTaskTag()
    {
        //	DB::table('sys_badge')->delete();
        //IP Sum Attributes
        DB::table('sys_task_tag')->truncate();
        MD\SysTaskTag::create(['name' => '动画制作', 'code' => '10001', 'hot' => '1']);
        MD\SysTaskTag::create(['name' => '网站制作', 'code' => '10002', 'hot' => '2']);
    }
    private function initSysUserSkill()
    {
        DB::table('sys_user_skill')->truncate();
        MD\SysUserSkill::create(['name' => '文案',  'hot' => '1', 'code' => '2001001']);
        MD\SysUserSkill::create(['name' => '编剧',  'hot' => '2', 'code' => '2001002']);
        MD\SysUserSkill::create(['name' => '音乐',  'hot' => '3', 'code' => '2001003']);
        MD\SysUserSkill::create(['name' => '配音',  'hot' => '4', 'code' => '2001004']);
        MD\SysUserSkill::create(['name' => '画师',  'hot' => '5', 'code' => '2001005']);
        MD\SysUserSkill::create(['name' => '设计',  'hot' => '6', 'code' => '2001006']);
        MD\SysUserSkill::create(['name' => '剪辑',  'hot' => '7', 'code' => '2001007']);
        MD\SysUserSkill::create(['name' => 'Coser', 'hot' => '8', 'code' => '2001008']);
        MD\SysUserSkill::create(['name' => '摄影', 'hot' => '9', 'code' => '2001009']);
        MD\SysUserSkill::create(['name' => '化妆', 'hot' => '10', 'code' => '2001010']);
        MD\SysUserSkill::create(['name' => '开发', 'hot' => '11', 'code' => '2001011']);
        MD\SysUserSkill::create(['name' => '策划',  'hot' => '12', 'code' => '2001012']);
        MD\SysUserSkill::create(['name' => 'PM',  'hot' => '13', 'code' => '2001013']);
        MD\SysUserSkill::create(['name' => '资料',  'hot' => '14', 'code' => '2001014']);
    }

    private function initUser()
    {
        //DB::table('t_user')->truncate();
        //仅当系统中不存在管理员账号的时候生成管理员账号
        if (DB::table('t_user')->where('role', 'admin')->count() > 0) {
            return;
        }
        $this->command->info('Init umeiii users');
        for ($i = 1; $i <= 10; ++$i) {
            if ($i < 10) {
                $num = '0'.$i;
            } else {
                $num = $i;
            }
            MD\User::create([
                    'password' => bcrypt('11111111'),
                    'display_name' => '测试'.$i,
                    'role' => 'admin',
                    'email' => 'test'.$num.'@umeiii.com',
                    'status' => 'activated', ]);
        }
    }
}
