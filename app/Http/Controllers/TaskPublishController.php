<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysUserSkill;
use App\Models\Task;
use App\Models\TaskTimeline;
use App\Models\TaskMilestone;
use App\Models\TaskDeliverCondition;
use App\Models\User;
use App\Models\SysTaskTag;
use App\Models\SysAttrEnum;
use App\Common\CommonUtils;
use App\Common\GoldManager;
use App\Common\TaskTimelineManager as TM;
use App\Common\GoldManager as GM;
use App\Common\TaskStep;
use App\Common\TaskAction;
use App\Common\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Common\Image;
use Auth;
class TaskPublishController extends Controller {

    const MIN_GUARANTEE_GOLD = 2000;
    const MIN_OPERATION_GOLD = 2000;
    //Create
    public function showCreatePage($mode=''){
        return $this->toTaskPage('create', ['defMode'=>$mode]);
    }
    //Manage Page
    public function showManagePage($pageName, $taskId){
        $task = Task::findOrFail($taskId);
        $actions = TaskAction::getActions($task);
        if(!TaskAction::checkAction($actions, TaskAction::MODIFY)){
            return errorPage();
        }
        $func = camel_case('get_'.$pageName.'_manage_page');
        return $this->$func($task, $actions);
    }
    private function getMainManagePage($task, $actions){
        $barActions = [];
        if(TaskAction::checkAction($actions, TaskAction::DELETE)){
            array_push($barActions, 'delete');
        }
        if(TaskAction::checkAction($actions, TaskAction::PREVIEW)){
            array_push($barActions, 'preview');
        }
        if(TaskAction::checkAction($actions, TaskAction::CANCEL)){
            array_push($barActions, 'cancel');
        }
        if(TaskAction::checkAction($actions, TaskAction::PUBLISH)){
            array_push($barActions, 'publish');
        }
        return $this->toTaskPage('mainedit', ['task'=>$task, 'bars'=>$barActions]);
    }
    private function getDetailManagePage($task){
        $token = csrf_token();
		$arr = Image::getUploadAliImageParams('image');
		$arr['id'] = $task->id;
        $arr['task'] = $task;

        //return $this->toTaskPage('editdetail', $arr);
        $taskModel = $task->model == TaskModel::PK?'pk':'appoint';
        return $this->toTaskPage('editrequirement', ['id'=>$task->id,
            'taskMode'=>$taskModel, 'isCreate' =>false, 'task'=>$task]);
    }
    private function getConditionManagePage($task){
        return $this->toTaskPage('editcondition', ['task'=>$task]);
    }
    //private function getMilestoneManagePage($task){
    //    return $this->toTaskPage('editmilestone', ['task'=>$task]);
    //}

    // data list
    public function getManageListData($listName, $taskId){
        if(!$this->checkListName($listName)) {
            return $this->toJsonResult(false, 'nolist', '');
        }
        $func = camel_case('get_'.$listName.'_list_data');
        return $this->$func($taskId);
    }
    private function getConditionListData($taskId){
        $conditions = TaskDeliverCondition::where('task_id', $taskId)->get();
        $resArr = [];
        foreach($conditions as $ms){
            array_push($resArr, [
                'label'    => $ms->label,
                'type'     => $ms->type,
                'typeName' => $ms->typeName,
                'value'    => empty($ms->value)?'':$ms->value,
                'text'     => $ms->text,
                'id'       => $ms->id
            ]);
        }
        return $this->toJsonResult(true, '', $resArr);
    }

    // partview
    public function loadManagePartview($partName, $id){
        if(!$this->checkPartviewName($partName)) {
            return $this->toErrorPage();
        }
        $func = camel_case('get_'.$partName.'_Partview');
        return $this->$func($id);

    }
    private function getTitlePartview($id){
        $value = ($id == 0)?'':(Task::findOrFail($id)->title);
        $arr = ['titleLabel'  => '编辑任务标题',
            'infoLabel'   => '请填写任务标题，标题长度不要超过20个汉字。',
            'value'       => $value,
            'maxlength'   => 20,
            'partName'    => 'title'];
        return $this->toTaskPartview('edpttext', $arr);
    }
    private function getAmountPartview($id){
        $isCreate = ($id == 0);
        $value = $isCreate?'1000':(Task::findOrFail($id)->amount);
        $arr = ['titleLabel'  => '编辑任务奖金',
            'infoLabel'   => '目前任务奖金为社区金币。',
            'postfixLabel'=> '金币',
            'isCreate'    => $isCreate,
            'value'       => $value,
            'partName'    => 'amount'];
        return $this->toTaskPartview('edptnumber', $arr);
    }
    private function getSkillTypePartview($id){
        $skills   = SysUserSkill::orderBy('hot', 'desc')->get();
        $skillArr = array();
        $defCode = '';
        foreach ($skills as $skill) {
            if(empty($defCode)) $defCode = $skill->code;
            $skillArr[$skill->code] = $skill->name;
        }
        $isCreate = ($id == 0);
        $value = $isCreate?$defCode:Task::findOrFail($id)->skill_type;
        $arr = ['titleLabel'    => '任务类型',
            'infoLabel'     => '请选择任务类型。',
            'sourceItems'   => $skillArr,//如果传入URL则从URL中读取数据，数组的话，则直接为选择项目
            'value'         => $value,
            'isCreate'      => $isCreate,
            'columns'       => 3,
            'partName'      => 'skill_type'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getSkillLevelPartview($id){
        $levels = SysAttrEnum::where('column', '20013')->get();
        $levelArr = ['0'=>'不限制技能等级'];
        foreach($levels as $level){
            $levelArr[$level->code] = $level->name;
        }
        $value = Task::findOrFail($id)->joinCondition['skill']['key'];
        $arr = ['titleLabel'    => '任务模式',
            'infoLabel'     => '任务创建后，任务模式将无法修改。',
            'sourceItems'   => $levelArr,
            'value'         => $value,
            'isCreate'      => false,
            'partName'      => 'skill_level'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getCreditLevelPartview($id){
        $levels = SysAttrEnum::where('column', '20014')->get();
        $levelArr = ['0'=>'不限制信誉等级'];
        foreach($levels as $level){
            $levelArr[$level->code] = $level->name;
        }
        $value = Task::findOrFail($id)->joinCondition['credit']['key'];
        $arr = ['titleLabel'    => '任务模式',
            'infoLabel'     => '任务创建后，任务模式将无法修改。',
            'sourceItems'   => $levelArr,
            'value'         => $value,
            'isCreate'      => false,
            'partName'      => 'credit_level'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getGuaranteePartview($id){
        $isCreate = ($id == 0);
        $value = $isCreate?'0':Task::findOrFail($id)->guarantee;
        $arr = ['titleLabel'    => '第三方评估',
            'infoLabel'     => '请选择第三方评估机构，选择第三评估机构会加收部分评估费用。',
            'sourceLink'    => '/source/value',//如果传入URL则从URL中读取数据，数组的话，则直接为选择项目
            'sourceItems'   => [0=>'不需要第三方评估', 1=>'有妹评估', 2=>'其他评估'],//如果传入URL则从URL中读取数据，数组的话，则直接为选择项目
            'itemDesc'      => [0=>'无额外费用。', 1=>'加收额外评估费用。费用不低于500金币。', 2=>'暂未开放。'],//
            'value'         => $value,
            'isCreate'      => $isCreate,
            'partName'      => 'guarantee'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getDeliveryDatePartview($id){
        $isCreate = ($id == 0);
        $value = $isCreate?date('Y-m-d'):Task::findOrFail($id)->delivery_date;
        $arr = ['titleLabel'    => '交付日期',
            'infoLabel'     => '请设置任务的交付日期，交付日期不能早于当前日期。',
            'value'         => $value,
            'isCreate'      => $isCreate,
            'partName'      => 'delivery_date'];
        return $this->toTaskPartview('edptdate', $arr);
    }
    private function getMaxPartnerCountPartview($id){
        $isCreate = ($id == 0);
        $value = $isCreate?'1000':(Task::findOrFail($id)->max_partner_count);
        $arr = ['titleLabel'  => '最大参与人数',
            'infoLabel'   => '设置pk任务的最大参与人数，0表示不做限制。',
            'postfixLabel'=> '人',
            'isCreate'    => $isCreate,
            'value'       => $value,
            'partName'    => 'max_partner_count'];
        return $this->toTaskPartview('edptnumber', $arr);
    }
    private function getAssignSolutionPartview($id){
        $asList = Task::getAssignSolutionList();
        $names = [];
        $descs = [];
        foreach($asList as $key=>$as){
           $names[$key] = $as['name'];
           $descs[$key] = $as['desc'];
        }
        $isCreate = ($id == 0);
        $value = $isCreate?'0':Task::findOrFail($id)->assign_solution['id'];
        $arr = ['titleLabel'    => '分配方案',
            'infoLabel'     => 'PK任务最终结果分配方案。',
            'sourceItems'   => $names,
            'itemDesc'      => $descs,
            'value'         => $value,
            'isCreate'      => $isCreate,
            'partName'      => 'assign_solution'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getTaskModelPartview($id){
        $arr = ['titleLabel'    => '任务模式',
            'infoLabel'     => '任务创建后，任务模式将无法修改。',
            'sourceItems'   => ['tenders'=>'PK模式', 'simple'=>'约定模式'],
            'value'         => 'tenders',
            'isCreate'      => true,
            'partName'      => 'task_model'];
        return $this->toTaskPartview('edptselector', $arr);
    }
    private function getMilestonePartview($id){
        if($id == 0){
            $ms = new TaskMilestone;
            $ms->date = date('Y-m-d');
        }else{
            $ms = TaskMilestone::findOrFail($id);
        }
        return $this->toTaskPartview('edptmilestone',['milestone'=>$ms]);
    }
    private function getNewConditionPartview($type){
        return $this->getConditionPartview(0, $type);
    }
    private function getConditionPartview($id, $type = 0){
        if($id == 0){
            $cd = new TaskDeliverCondition;
            $cd->type = $type;
            if($type == 1){
                $cd->value = date('Y-m-d');
            }elseif($type == 2){
                $cd->value = 1;
            }elseif($type == 5){
                $cd->value = '';
            }
        }else{
            $cd = TaskDeliverCondition::findOrFail($id);
        }
        $arr = [];
        if($type == 5){
            $arr['accessId'] = CommonUtils::getAliOssAccessId();
            $arr['policy'] = CommonUtils::getAliOSSPostPolicy();
            $arr['signature'] = Commonutils::getAliOSSSignature($arr['policy']);
            $arr['postUrl'] = CommonUtils::getAliUrl('post');
            $arr['showUrl'] = CommonUtils::getAliUrl('show');
            $arr['nameSeed'] = Commonutils::createRandomId('role');
            $arr['token'] = Commonutils::createUserToken(Auth::user()->id);
        }
        $arr['condition'] = $cd;
        return $this->toTaskPartview('edptcondition', $arr);
    }

    public function createTask(Request $request){
        $user_id = Auth::user()->id;
        $task    = Task::create([
            'title'         => $request->input('title'),
            'task_type'     => $request->input('taskModel'),
            'amount'        => $request->input('amount'),
            'delivery_date' => $request->input('deliveryDate'),
            'skill_type'    => $request->input('skillType'),
            'step'          => Task::TASKSTEP_WAIT_PUBLISH,
            'user_id'       => $user_id]);
        return $this->toJsonResult(true,'',$task->id);
    }
    public function removeTask(Request $request){
        $taskId = $request->input('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::DELETE)){
            return errorPage();
        }
        TaskTimeLine::where('task_id', $taskId)->delete();
        TaskDeliverCondition::where('task_id', $taskId)->delete();
        $task->delete();
        return $this->toJsonResult(true);
    }
    public function cancelTask(Request $request){
        $taskId = $request->input('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::CANCEL)){
            return errorPage();
        }
        $task->step = TaskStep::CANCEL;
        $task->save();
        $this->cancelGoldGuarantee($taskId);
        return $this->toJsonResult(true);
    }
    public function publishTask(Request $request){
        $taskId = $request->input('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::PUBLISH)){
            return errorPage();
        }
        $checkRes = $this->checkTaskParamters($task);
        if(!$checkRes['result']){
            $errStr = implode(' ', $checkRes['info']);
            return $this->toJsonResult(false, $errStr);
        }
        if(!$this->goldGuarantee($task)){
            return $this->toJsonResult(false, '账户余额不足，无法发布任务。');
        }
        $task->step = Task::TASKSTEP_WAIT_JOIN;
        $task->publish_date = date('Y-m-d');
        $task->save();
        return $this->toJsonResult(true);
    }
    private function goldGuarantee($task){
        //金额担保
        $amount = $task->amount;
        $extraGold = 0;
        //约定任务需要第三方的评审
        if($task->model == TaskModel::APPOINT && $task->guarantee > 0){
            $extraGold = $amount * Task::APPOINT_SERVICE_PERCENT;
            if($extraGold < Task::MIN_APPOINT_SERVICE_GOLD){
                $extraGold = Task::MIN_APPOINT_SERVICE_GOLD;
            }
        }
        //pk任务
        if($task->model == TaskModel::PK){
            $extraGold = $amount * Task::PK_SERVICE_PERCENT;
            if($extraGold < Task::MIN_PK_SERVICE_GOLD){
                $extraGold = Task::MIN_PK_SERVICE_GOLD;
            }
        }
        $result = GoldManager::guaranteeDeal(Auth::id(), ($amount + $extraGold), '5000123', $task->id);
        return $result['result'] == 1;
    }
    
    private function cancelGoldGuarantee($taskId){
        $gid = GoldManager::findGuaranteeId('task', $taskId);
        $res = GoldManager::finishGuaranteeDeal($gid, 3, '任务开始前取消');   
        return $res['result'] == 1;
    }
    public function saveParameter(Request $request){
        $taskId = $request->input('taskId');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::MODIFY)){
            return $this->toJsonResult(false,'无操作权限');
        }
        $paramName = $request->input('paramName');
        $paramMap = [
            'title'             => 'title',
            'amount'            => 'amount',
            'skill_type'        => 'skill_type',
            'guarantee'         => 'guarantee',
            'delivery_date'     => 'delivery_date',
            'delivery_type'     => 'delivery_type',
            'skill_level'       => 'skill_level',
            'credit_level'      => 'credit_level',
            'max_partner_count' => 'max_partner_count',
            'assign_solution'   => 'assign_solution'
        ];
        if(!array_key_exists($paramName, $paramMap)){
            return $this->toJsonResult(false,'无效目标。');
        }
        $task->$paramMap[$paramName] = $request->input('value');
        $task->save();
        return $this->toJsonResult(true);
    }
    public function saveDetail(Request $request){
        $taskId = $request->input('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::MODIFY)){
            return $this->toJsonResult(false,'无操作权限');
        }
        $task->intro = $request->input('taskIntro');
        $task->image = $request->input('taskImg');
        $task->skill_type = $request->input('skillType');
        if($task->model == TaskModel::PK){
            $task->review_img = $request->input('reviewImg');
            $task->review_intro = $request->input('reviewIntro');
        }
        $task->save();
        //return redirect(noBackUrl('/pubtask/manage-main/'.$task->id, 2));
        return redirect('/page/loading/back_2');
    }
    // 保存条件
    public function saveCondition(Request $request){
        $id = $request->input('id');
        if($id == 0){
            $taskId = $request->input('taskId');
            $task = Task::findOrFail($taskId);
            if(!$this->checkAuthority($task)){
                return $this->toJsonResult(false,'无操作权限');
            }
            $condition = new TaskDeliverCondition;
            $condition->task_id = $taskId;
            $condition->user_id = Auth::user()->id;
            $condition->type = $request->input('type');
        }else{
            $condition = TaskDeliverCondition::findOrFail($id);
            if(!$this->checkAuthority($condition->task)){
                return $this->toJsonResult(false,'无操作权限');
            }
        }
        if(in_array($condition->type, ['1','2','5'])){
            $condition->value = $request->input('value');
        }
        $condition->label = $request->input('label');
        $condition->text = $request->input('text');
        $condition->save();
        return $this->toJsonResult(true);
    }
    //删除条件
    public function removeCondition(Request $request)
    {
        $conditionId = $request->input('conditionId');
        $condition   = TaskDeliverCondition::findOrFail($conditionId);
        if(!$this->checkAuthority($condition->task)){
            return $this->toJsonResult(false,'无操作权限');
        }
        if ($condition != null) {
            $value = $condition->label;
            $condition->delete();
        }
        return $this->toJsonResult(true);
    }
    //common
    private function checkPageName($pageName){
        $pageArr = ['main', 'detail', 'condition', 'milestone'];
        return in_array($pageName, $pageArr);
    }
    private function checkListName($listName){
        $listArr = ['condition', 'milestone'];
        return in_array($listName, $listArr);
    }
    private function checkPartviewName($partviewName){
        $viewArr = [ 'title', 'amount', 'skill_type', 'skill_level',
            'credit_level', 'guarantee', 'delivery_date', 'max_partner_count', 'assign_solution',
            'task_model', 'milestone', 'condition', 'new_condition'];
        return in_array($partviewName, $viewArr);
    }
    private function checkAuthority($task){
        $isBelongUser = $task->user_id == Auth::user()->id;
        $isNotPublish = $task->status == 0;
        return $isBelongUser && $isNotPublish;
    }
    private function checkTaskParamters($task){
        $result['result'] = true;
        $result['info'] = [];
        if(strtotime($task->delivery_date)<= strtotime(date('Y-m-d'))){
            array_push($result['info'], '交付日期需要晚于今天。');
            $result['result'] = false;
        }
        if(!$task->isSetDetail){
            array_push($result['info'], '未填写任务详细信息。');
            $result['result'] = false;
        }
        return $result;
    }
    private function toTaskPage($name, $parameters=[]){
        return view('task.'.$name, $parameters);
    }
    private function toTaskPartview($name, $parameters){
        return view('task.partview.'.$name, $parameters);
    }
    private function toJsonResult($result, $desc='', $value=''){
        $arr['res'] = $result;
        $arr['desc'] = $desc;
        $arr['value'] = $value;
        return response()->json($arr);
    }
    private function toErrorPage($type=''){}
}
