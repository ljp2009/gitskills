<?php

use App\Models\GameDataModel as MD;
use Illuminate\Database\Seeder;

class InitGameDataSeeder extends Seeder
{
    private static $INT = 'int';
    private static $FLOAT = 'float';
    private static $STRING = 'string';
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('game_data')->delete();

        $this->command->info('Start prepare game data');

        $this->initData();

        $this->command->info('Finished successfully');
    }

    private function createData($data, $name, $nameDescription, $diffDescription, $dataDescription, $dataType = 'float', $diffInData = false, $startfrom = 1, $increment = true,
                $extra1Description = ' ', $extra1Type = 'float', $extra2Description = ' ', $extra2Type = 'float', $extra3Description = ' ', $extra3Type = 'float')
    {
        if (is_array($data)) {
            $ct = 0;
            foreach ($data as $onedata) {
                $lendata = sizeof($onedata);
                $startindex = 0;
                if (!$diffInData) {
                    if ($increment) {
                        $diff = $startfrom + $ct;
                    } else {
                        $diff = $startfrom - $ct;
                    }
                } else {
                    $diff = intval($onedata[0]);
                    $startindex = 1;
                }
                ++$ct;

                $dataarr = [];
                $dataarr['name'] = $name;
                $dataarr['name_description'] = $nameDescription;
                $dataarr['diff'] = $diff;
                $dataarr['diff_description'] = $diffDescription;
                $dataarr['data'] = $onedata[$startindex];
                $dataarr['data_description'] = $dataDescription;
                $dataarr['data_type'] = $dataType;

                ++$startindex;
                if ($lendata <= $startindex) {
                    MD::create($dataarr);
                    continue;
                }

                $dataarr['extra_data1'] = $onedata[$startindex];
                $dataarr['extra_data1_description'] = $extra1Description;
                $dataarr['extra_data1_type'] = $extra1Type;

                ++$startindex;
                if ($lendata <= $startindex) {
                    MD::create($dataarr);
                    continue;
                }

                $dataarr['extra_data2'] = $onedata[$startindex];
                $dataarr['extra_data2_description'] = $extra2Description;
                $dataarr['extra_data2_type'] = $extra2Type;

                ++$startindex;
                if ($lendata <= $startindex) {
                    MD::create($dataarr);
                    continue;
                }

                $dataarr['extra_data3'] = $onedata[$startindex];
                $dataarr['extra_data3_description'] = $extra3Description;
                $dataarr['extra_data3_type'] = $extra3Type;

                MD::create($dataarr);
            }
        } else {
            MD::create(['name' => $name, 'name_description' => $nameDescription, 'diff' => 0, 'diff_description' => $diffDescription,
                'data' => strval($data), 'data_description' => $dataDescription, 'data_type' => $dataType, ]);
        }
    }

    private function initData()
    {
        $maxTurns = 15;

        $this->createData($maxTurns, 'maxTurns', '游戏最多的轮数', ' ', '游戏最多的轮数', self::$INT);

        $singleAOEHurtDiff = 0.6; //1.8/3

        $this->createData($singleAOEHurtDiff, 'singleAOEHurtDiff', '群攻vs.单攻伤害差别', ' ', '群攻vs.单攻伤害差别', self::$FLOAT);

        $restSummonToLuckPoint = 5;

        $this->createData($restSummonToLuckPoint, 'restSummonToLuckPoint', '召唤点兑换Luck Point比', ' ', '召唤点兑换Luck Point比', self::$INT);

        $tiToBlood = 30;

        $this->createData($tiToBlood, 'tiToBlood', '体力和血量关系', ' ', '体力和血量关系', self::$INT);

        $userLevels = [[120, 8], [122, 8], [124, 8], [126, 8], [128, 9], [130, 9], [132, 9], [134, 9], [136, 9], [138, 10], [140, 10],
                            [142, 10], [144, 10], [146, 10], [148, 12], [150, 12], [152, 12], [154, 12], [156, 12], [158, 14], [160, 14], [162, 14],
                            [164, 14], [166, 14], [168, 16], [170, 17], [172, 18], [174, 19], [176, 20], [180, 21], ];

        $this->createData($userLevels, 'userLevels', '玩家级别设定', '级别', '点数', self::$INT, false, 1, true, '召唤点数', self::$INT);

        $heroLevels = [['青铜', 0.1, 1, 1], ['白银', 0.2, 2, 2], ['黄金', 0.3, 3, 3], ['钻石', 0.4, 4, 4], ['王者', 0.5, 5, 5]];

        $this->createData($heroLevels, 'heroLevels', '英雄级别设定', '级别', '级别名称', self::$STRING, false, 1, true,
                '特殊牌型给抽取机率', self::$FLOAT, '牌型所需召唤点数', self::$INT, '默认幸运点数', self::$INT);

        $heroBloodLuck = ['50' => 1, '30' => 2, '10' => 3];
        $convertBloodLuck = [];
        foreach ($heroBloodLuck as $k => $v) {
            array_push($convertBloodLuck, array(intval($k), $v));
        }
        $this->createData($convertBloodLuck, 'heroBloodLuck', '英雄血量和幸运点数关系', '英雄血量百分比', '幸运点数', self::$INT, true);

        $heroSkills = [['单体攻', 'single', 'attack'], ['全体攻', 'all', 'attack'], ['单体控伤', 'single', 'control'], ['全体控伤', 'all', 'control'],
                ['单体控补血', 'single', 'recover', 'blood'], ['全体控补血', 'all', 'recover', 'blood'],
                ['单体控增益', 'single', 'recover', 'enhance'], ['全体控增益', 'all', 'recover', 'enhance'], ];

        $this->createData($heroSkills, 'heroSkills', '英雄技能列表', '序号', '技能名称', self::$STRING, false, 1, true, '攻击范围', self::$STRING,
                '技能类型', self::$STRING, '控制类型', self::$STRING);

        $heroSuperSkills = [['单体攻', 'single', 'attack'], ['全体攻', 'all', 'attack'], ['单体控伤', 'single', 'control'], ['全体控伤', 'all', 'control']];

        $this->createData($heroSuperSkills, 'heroSuperSkills', '英雄必杀技列表', '序号', '技能名称', self::$STRING, false, 1, true, '攻击范围', self::$STRING,
                '技能类型', self::$STRING);

       //社区用户属性与游戏的属性对接
        $featureRatesEnum = [[0.1, 5], [0.25, 4], [0.5, 3], [0.75, 2], [1, 1]];

        $this->createData($featureRatesEnum, 'featureRatesEnum', '社区用户属性与游戏的属性对接', '序号', '设定比例',
            self::$FLOAT, false, 1, true, '增益值', self::$INT);

         //连击速度差值
        $heroLianSpeedDiff = ['D1' => 0.1, 'D2' => 0.15, 'D3' => 0.2, 'D4' => 0.28, 'D5' => 0.36, 'D6' => 0.44, 'D5' => 0.52, 'D6' => 0.62, 'D7' => 0.75];
        $conv = array();
        foreach ($heroLianSpeedDiff as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'heroLianSpeedDiff', '普通攻击连击几率', '速度差值', '几率',
            self::$FLOAT);

        //暴击攻击差值
        $heroBaoAttackDiff = ['D1' => 0.1, 'D2' => 0.15, 'D3' => 0.2, 'D4' => 0.28, 'D5' => 0.36, 'D6' => 0.44, 'D5' => 0.52, 'D6' => 0.62, 'D7' => 0.75];
        $conv = array();
        foreach ($heroBaoAttackDiff as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'heroBaoAttackDiff', '普通攻击暴击几率', '攻击差值', '几率',
            self::$FLOAT);

        //躲避速度差值
        $heroMissSpeedDiff = ['D1' => 0.1, 'D2' => 0.15, 'D3' => 0.2, 'D4' => 0.25, 'D5' => 0.30, 'D6' => 0.35, 'D7' => 0.4, 'D8' => 0.45, 'D9' => 0.5];
        $conv = array();
        foreach ($heroMissSpeedDiff as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'heroMissSpeedDiff', '普通攻击躲避几率', '速度差值', '几率',
            self::$FLOAT);

        //技能躲避速度差值
        $heroJiMissSpeedDiff = ['D1' => 0.1, 'D2' => 0.15, 'D3' => 0.2, 'D4' => 0.25, 'D5' => 0.30, 'D6' => 0.35, 'D7' => 0.4, 'D8' => 0.45, 'D9' => 0.5];
        $conv = array();
        foreach ($heroJiMissSpeedDiff as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'heroJiMissSpeedDiff', '技能攻击躲避几率', '速度差值', '几率',
            self::$FLOAT);

        //防御减免防御差值
        $heroProtectDiff = ['D1' => 0.10, 'D2' => 0.15, 'D3' => 0.20, 'D4' => 0.22, 'D5' => 0.25, 'D6' => 0.27, 'D7' => 0.29, 'D8' => 0.31, 'D9' => 0.34, 'D10' => 0.36,
                                    'D11' => 0.38, 'D12' => 0.40, 'D13' => 0.42, 'D14' => 0.44, 'D15' => 0.46, 'D16' => 0.48, 'D17' => 0.50, 'D18' => 0.52, 'D19' => 0.54,
                                        'D20' => 0.56, 'D21' => 0.58, 'D22' => 0.60, 'D23' => 0.62, 'D24' => 0.64, 'D25' => 0.66, 'D26' => 0.68, 'D27' => 0.70, 'D28' => 0.72, 'D29' => 0.73,
                                        'D30' => 0.74, 'D31' => 0.75, 'D32' => 0.76, 'D33' => 0.77, 'D34' => 0.78, 'D35' => 0.79, 'D36' => 0.80, ];
        $conv = array();
        foreach ($heroProtectDiff as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'heroProtectDiff', '攻击减免', '防御差值', '减免百分比',
            self::$FLOAT);

        //合防减免防御差值
        $heroTogetherProtectDiff = [
            'D1' => [0.01, 0.0], 'D2' => [0.02, 0.0], 'D3' => [0.03, 0.0], 'D4' => [0.04, 0.0], 'D5' => [0.05, 0.0], 'D6' => [0.06, 0.0], 'D7' => [0.07, 0.0], 'D8' => [0.08, 0.0], 'D9' => [0.09, 0.0],
            'D10' => [0.10, 0.0], 'D11' => [0.11, 0.0], 'D12' => [0.12, 0.0], 'D13' => [0.13, 0.0], 'D14' => [0.14, 0.0], 'D15' => [0.15, 0.0], 'D16' => [0.16, 0.0], 'D17' => [0.17, 0.0],
            'D18' => [0.18, 0.0], 'D19' => [0.19, 0.0], 'D20' => [0.20, 0.0], 'D21' => [0.21, 0.0], 'D22' => [0.22, 0.0], 'D23' => [0.23, 0.0], 'D24' => [0.24, 0.0], 'D25' => [0.25, 0.0],
            'D26' => [0.26, 0.0], 'D27' => [0.27, 0.0], 'D28' => [0.28, 0.0], 'D29' => [0.29, 0.0], 'D30' => [0.30, 0.5], 'D31' => [0.31, 0.6], 'D32' => [0.32, 0.7], 'D33' => [0.33, 0.8],
            'D34' => [0.34, 0.9], 'D35' => [0.35, 0.10], 'D36' => [0.36, 0.11], 'D37' => [0.37, 0.12], 'D38' => [0.38, 0.13], 'D39' => [0.39, 0.14], 'D40' => [0.40, 0.15], 'D41' => [0.41, 0.16],
            'D42' => [0.42, 0.17], 'D43' => [0.43, 0.18], 'D44' => [0.44, 0.19], 'D45' => [0.45, 0.20], 'D46' => [0.46, 0.21], 'D47' => [0.47, 0.22], 'D48' => [0.48, 0.23], 'D49' => [0.49, 0.24],
            'D50' => [0.50, 0.25], 'D51' => [0.51, 0.26], 'D52' => [0.52, 0.27], 'D53' => [0.53, 0.28], 'D54' => [0.54, 0.29], 'D55' => [0.55, 0.30], 'D56' => [0.56, 0.31], 'D57' => [0.57, 0.32],
            'D58' => [0.58, 0.33], 'D59' => [0.59, 0.34], 'D60' => [0.60, 0.35], 'D61' => [0.61, 0.36], 'D62' => [0.62, 0.37], 'D63' => [0.63, 0.38], 'D64' => [0.64, 0.39], 'D65' => [0.65, 0.40],
            'D66' => [0.66, 0.41], 'D67' => [0.67, 0.42], 'D68' => [0.68, 0.43], 'D69' => [0.69, 0.44], 'D70' => [0.70, 0.45], 'D71' => [0.71, 0.46], 'D72' => [0.72, 0.47], 'D73' => [0.73, 0.48],
            'D74' => [0.74, 0.49], 'D75' => [0.75, 0.50], 'D76' => [0.76, 0.51], 'D77' => [0.77, 0.52], 'D78' => [0.78, 0.53], 'D79' => [0.79, 0.54], 'D80' => [0.80, 0.55], 'D81' => [0.81, 0.56],
            'D82' => [0.82, 0.57], 'D83' => [0.83, 0.58], 'D84' => [0.84, 0.59], 'D85' => [0.85, 0.60], 'D86' => [0.86, 0.61], 'D87' => [0.87, 0.62], 'D88' => [0.88, 0.63], 'D89' => [0.89, 0.64],
            'D90' => [0.90, 0.65],
        ];
        $conv = array();
        foreach ($heroTogetherProtectDiff as $k => $v) {
            array_push($conv, $v);
        }
        $this->createData($conv, 'heroTogetherProtectDiff', '合防攻击减免', '联合防御差值', '减免百分比',
            self::$FLOAT, false, 1, true, '合击几率');

        //card level=> rate
        $luckDiffSpecialRateStage = [['C12' => 0.05, 'C11' => 0.1, 'C10' => 0.2, 'C9' => 0.3, 'C8' => 0.35],
                                         ['C12' => 0.02, 'C11' => 0.03, 'C10' => 0.1, 'C9' => 0.3, 'C8' => 0.55],
                                         ['C12' => 0.01, 'C11' => 0.02, 'C10' => 0.07, 'C9' => 0.4, 'C8' => 0.5],
                                         ['C12' => 0.01, 'C11' => 0.01, 'C10' => 0.06, 'C9' => 0.32, 'C8' => 0.6], ];
        $idx = 0;
        foreach ($luckDiffSpecialRateStage as $oneluckdiffstage) {
            $stageName = 'Stage'.$idx;
            ++$idx;
            $luckvalue = array();
            foreach ($oneluckdiffstage as $k => $v) {
                array_push($luckvalue, array($v));
            }
            $this->createData($luckvalue, $stageName, '特殊牌型分配几率'.$idx, '牌型',
                '抽取几率', self::$FLOAT, false, 12, false);
        }

        //diff=>[rate, stage]
        $luckDiffSpecialRate = ['D33' => [60 / 100, 'Stage0'], 'D32' => [59 / 100, 'Stage0'], 'D31' => [58 / 100, 'Stage0'], 'D30' => [57 / 100, 'Stage0'], 'D29' => [56 / 100, 'Stage0'], 'D28' => [55 / 100, 'Stage0'], 'D27' => [54 / 100, 'Stage0'],
            'D26' => [53 / 100, 'Stage0'], 'D25' => [52 / 100, 'Stage0'], 'D24' => [51 / 100, 'Stage0'], 'D23' => [50 / 100, 'Stage0'], 'D22' => [49 / 100, 'Stage0'], 'D21' => [48 / 100, 'Stage0'], 'D20' => [47 / 100, 'Stage0'], 'D19' => [46 / 100, 'Stage0'],
            'D18' => [45 / 100, 'Stage1'], 'D17' => [44 / 100, 'Stage1'], 'D16' => [43 / 100, 'Stage1'], 'D15' => [42 / 100, 'Stage1'], 'D14' => [41 / 100, 'Stage1'],
            'D13' => [40 / 100, 'Stage2'], 'D12' => [39 / 100, 'Stage2'], 'D11' => [38 / 100, 'Stage2'], 'D10' => [37 / 100, 'Stage2'], 'D9' => [36 / 100, 'Stage2'], 'D8' => [35 / 100, 'Stage2'], 'D7' => [34 / 100, 'Stage2'], 'D6' => [33 / 100, 'Stage2'], 'D5' => [32 / 100, 'Stage2'],
            'D4' => [31 / 100, 'Stage3'], 'D3' => [30 / 100, 'Stage3'], 'D2' => [29 / 100, 'Stage3'], 'D1' => [28 / 100, 'Stage3'], 'D0' => [27 / 100, 'Stage3'], ];
        $this->createData($luckDiffSpecialRate, 'luckDiffSpecialRate', '特殊牌型抽取机率', '幸运点数差值',
            '抽取几率', self::$FLOAT, false, 33, false, '特殊牌型分配几率', self::$STRING);

        $userLuckPointAndWIN = [
            'WIN1' => 18, 'WIN2' => 16, 'WIN3' => 14, 'WIN4' => 12, 'WIN5' => 10, 'WIN6' => 8, 'WIN7' => 6, 'WIN8' => 4, 'WIN9' => 2, 'WIN10' => 0,
        ];
        $conv = array();
        foreach ($userLuckPointAndWIN as $k => $v) {
            array_push($conv, array($v));
        }
        $this->createData($conv, 'userLuckPointAndWIN', '连胜和幸运点关系', '连胜次数', '幸运点数',
            self::$INT);

        //必杀技（ＡＯＥ）命中
        $superSkillMiss = [
            0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00,
            0.00, 0.05, 0.07, 0.09, 0.11, 0.13, 0.15, 0.17, 0.19, 0.21, 0.24, 0.27, 0.30,
            0.33, 0.37, 0.44, 0.60, 0.76, 0.92, 1.00, 1.00, 1.00, 1.00, 1.00,
        ];
        $conv = array();
        $idx = 0;
        foreach ($superSkillMiss as $oneMiss) {
            array_push($conv, array(18 - $idx, $oneMiss));
            ++$idx;
        }
        $this->createData($conv, 'superSkillMiss', '必杀技（ＡＯＥ）命中', '（速＋技）差值', '命中几率',
            self::$FLOAT, true);
    }
}
