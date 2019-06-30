@extends('layouts.publish')
@section('title',  '添加任务详情')
@section('formrange')

<?php
use App\Models\Task;

$oneform = Publish::form('/pubtask/edit/info', '编辑任务基本信息');
$oneform->addComp(array(
    'name' => 'task_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $task->id,
));
$oneform->addComp(array(
    'name' => 'target',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $target,
));
$oneform->addComp(array(
    'name' => 'delivery_type',
    'type' => Publish::$TP_COMBO,
    'defaultValue' => $task->delivery_type,
    'selectitems' => $deliveryTypes,
    'label' => '交付方式',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'amount',
    'type' => Publish::$TP_TEXT,
    'defaultValue' => intval($task->amount),
    'label' => '佣金（金币）',
    'validators' => array(Publish::$VAL_REQUIRED, Publish::$VAL_INTEGER),
));
$oneform->addComp(array(
    'name' => 'skill_type',
    'type' => Publish::$TP_COMBO,
    'defaultValue' => $task->skill_type,
    'selectitems' => $skills,
    'label' => '任务分类',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'skill_level',
    'type' => Publish::$TP_COMBO,
    'defaultValue' => $task->skill_level,
    'selectitems' => $skLevels,
    'label' => '用户技能限制（技能最低标准）',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'credit_level',
    'type' => Publish::$TP_COMBO,
    'defaultValue' => $task->credit_level,
    'selectitems' => $creditLevels,
    'label' => '用户信誉限制（信誉最低标准）',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'delivery_date',
    'type' => Publish::$TP_DATE,
    'defaultValue' => is_null($task->delivery_date) ? date('Y-m-d') : $task->delivery_date,
    'label' => '交付日期',
    'validators' => array(Publish::$VAL_REQUIRED),
));
if ($task->task_type == Task::TASKTYPE_PK) {
    $oneform->addComp(array(
        'name' => 'max_partner_count',
        'type' => Publish::$TP_TEXT,
        'defaultValue' => is_null($task->max_partner_count) ? 0 : $task->max_partner_count,
        'label' => '最大PK人数（0为无限制）',
        'validators' => array(Publish::$VAL_REQUIRED, Publish::$VAL_INTEGER),
    ));
    $oneform->addComp(array(
        'name' => 'assign_solution',
        'type' => Publish::$TP_COMBO,
        'defaultValue' => $task->assign_solution,
        'selectitems' => ['1' => '1:50%; 2:40%; +:10%', '2' => '1:80%; +:20%'],
        'label' => '分配方案',
        'validators' => array(Publish::$VAL_REQUIRED),
    ));
} elseif ($task->task_type == Task::TASKTYPE_APPOINT) {
    $oneform->addComp(array(
        'name' => 'guarantee',
        'type' => Publish::$TP_COMBO,
        'defaultValue' => $task->guarantee,
        'selectitems' => ['0' => '不使用第三方担保', '2' => '有妹担保'],
        'label' => '第三方担保（有偿担保）',
        'validators' => array(Publish::$VAL_REQUIRED),
    )); ?>
<pre>第三方评估说明：
需求方提供任务，并提供详细的可衡量的任务标准说明。选定有意向用户进行任务后。双方达成约定后，双方共同指定承担最后仲裁的第三方，并由需求方给付第三方10%仲裁费用，任务正式生效。接单用户根据需求方的时间与任务标准提交制作物。如用户按时提供交付物后，需求方不满意，可与接单方协议解决。但是如果有争议，由需求方提供平台违返制作约定的举证。第三方接受后可以协议退回任务费用。由第三方正式通知平台方，由平台方执行。</pre>
</pre>
<?php

}
if ($target == 'next') {
    $oneform->end('下一步（任务详情）', 'navbar');
} else {
    $oneform->end(['保存', ['name' => '返回', 'func' => 'history.back()']], 'navbar');
}
?>
@stop
@section('scriptrange')
@stop


