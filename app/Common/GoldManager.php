<?php
/**
 * 用户金币系统管理类
 * 用户收入、支出、结果，担保交易
 * @author xiaocui
 * @date 2016-05-16
 */
namespace App\Common;

use App\Models\UserDetailStatus;
use App\Models\UserGoldRecord;
use App\Models\UserGuaranteeDeal;
use App\Models\UserGuaranteeRecord;
use App\Models\User;
use Auth,Config;

class GoldManager
{
	
	/**
	 * 金币收入接口
	 * @param integer $incomeGold 收入金币数
	 * @param string  $incomeType 交易类型
	 * @param integer $resourceID 关联id
	 * @param integer $userID 用户
	 * @param string  $remark 备注
	 * @param boolean $inWallet 收费收入零钱袋子
	 * @return array $result['result']:结果，1：成功 0：失败  $result['deal_id'] :交易记录id  $result['current_gold']：当前金币数
	 */
	public static function incomeGold($incomeGold, $incomeType, $resourceID, $userID, $remark='', $inWallet = false)
	{
		$incomeTypes = Config::get('app.dealType');
		$result = ['result'=>'','msg'=>''];
		if(!array_key_exists($incomeType,$incomeTypes)){
			$result['result'] = 0;
			$result['msg'] = '收入类型不存在';
			return $result;
		}
		$detailStatus = UserDetailStatus::where('user_id',$userID)->first();
        if(is_null($detailStatus)){
            $detailStatus = new UserDetailStatus;
            $detailStatus->gold = 0;
            $detailStatus->receive_gold = 0;
            $detailStatus->pay_all = 0;
            $detailStatus->income_all = 0;
            $detailStatus->user_id = $userID;
        }
        if($inWallet){
            //收入到零钱包中
            $detailStatus->receive_gold = $detailStatus->receive_gold + $incomeGold;
        }else{
            //收入到金库中
            $detailStatus->gold = $detailStatus->gold + $incomeGold;
        }
		$detailStatus->income_all = $detailStatus->income_all + $incomeGold;
		$detailStatus->save();
		$id = self::addUserRecord($userID, $incomeType, $resourceID, 'income', $incomeGold, UserGoldRecord::INCOME_GOLD_SUCCESS, $remark);
		$result['result'] = 1;
		$result['deal_id'] = $id;
		$result['current_gold'] = $detailStatus->gold;
		return $result;
	}
	
	/**
	 * 金币支出接口
	 * @param unknown $payGold 支出金币数
	 * @param unknown $payType 交易类型
	 * @param unknown $resourceID 关联id
	 * @param unknown $userID 用户
	 * @param unknown $remark 备注
	 * @return array $result['result']:结果，1：成功 0：失败  $result['deal_id'] :交易记录id  $result['current_gold']：当前金币数
	 */
	public static function payGold($payGold,$payType,$resourceID,$userID,$remark='')
	{
		$detailStatus = UserDetailStatus::where('user_id',$userID)->first();
        if(is_null($detailStatus)){
            $detailStatus = new UserDetailStatus;
            $detailStatus->gold = 0;
            $detailStatus->pay_all = 0;
            $detailStatus->income_all = 0;
            $detailStatus->user_id = $userID;
        }
		$payTypes = Config::get('app.dealType');
		$result = ['result'=>'','msg'=>''];
		if(!array_key_exists($payType,$payTypes)){
			$result['result'] = 0;
			$result['msg'] = '支出类型不存在';
			return $result;
		}
		if($detailStatus->gold >= $payGold){//判断余额是否充足
			$detailStatus->gold = $detailStatus->gold - $payGold;
			$detailStatus->pay_all = $detailStatus->pay_all + $payGold;
			$detailStatus->save();
		}else{
			$id = self::addUserRecord($userID, $payType, $resourceID, 'pay', $payGold, UserGoldRecord::PAY_GOLD_FALE,$remark);
			$result['result'] = 0;
			$result['msg'] = '金币余额不足';
			$result['deal_id'] = $id;
			$result['current_gold'] = $detailStatus->gold;
			return $result;
		}
		$id = self::addUserRecord($userID, $payType, $resourceID, 'pay', $payGold, UserGoldRecord::PAY_GOLD_SUCCESS,$remark);
		$result['result'] = 1;
		$result['deal_id'] = $id;
		$result['current_gold'] = $detailStatus->gold;
		return $result;
	}
	
	/**
	 * 发起担保交易接口
	 * @param unknown $userID 用户id 
	 * @param unknown $guaranteeGold 担保金额
	 * @param unknown $guaranteeType 担保类型
	 * @param unknown $resourceID 关联id
	 * @param unknown $remark 备注
	 * @param number $status 担保转态，默认 1：担保中，可以不传
	 * @return array $result['result']:结果，1：成功 0：失败  $result['guarantee_id'] :担保id  $result['current_gold']：当前金币数
	 */
	public static function guaranteeDeal($userID,$guaranteeGold,$guaranteeType,$resourceID,$remark='',$guaranteeStatus = 1)
	{
		$guaranteeTypes = Config::get('app.dealType');
		$result = ['result'=>'','msg'=>''];
		if(!array_key_exists($guaranteeType,$guaranteeTypes)){
			$result['result'] = 0;
			$result['msg'] = '担保类型不存在';
			return $result;
		}
		$payResult = self::payGold($guaranteeGold, $guaranteeType, $resourceID, $userID, $remark);
		if($payResult['result'] == 1){//支出成功
			//生成担保的数据
			$guaranteeID = self::addGuaranteeDeal($userID, $guaranteeGold, $guaranteeType, $resourceID,$guaranteeStatus);
			//生成担保操作记录
			self::addGuaranteeRecord($userID, $guaranteeID, $payResult['deal_id'], 1, $remark);
			$result['result'] = 1;
			$result['guarantee_id'] = $guaranteeID;
			$result['current_gold'] = isset($payResult['current_gold']) ? $payResult['current_gold'] : '';
		}else{//支出失败
			$result['result'] = 0;
			$result['guarantee_id'] = 0;
			$result['current_gold'] = isset($payResult['current_gold']) ? $payResult['current_gold'] : '';
			$result['msg'] = $payResult['msg'];
		}
		return $result;
	}
	
	/**
	 * 担保交易追加接口
	 * @param unknown $guaranteeID  担保id
	 * @param unknown $userID 	             用户ID
	 * @param unknown $appendGold   追加金币数
	 * @param unknown $appendReason 追加备注
	 * @return array $result['result']:结果，1：成功 0：失败  $result['guarantee_id'] :担保id  支出成功或失败会返回 ：$result['current_gold']：当前金币数
	 */
	public static function appendGuaranteeDeal($guaranteeID,$userID,$appendGold,$appendReason='')
	{
		$guarantee = UserGuaranteeDeal::find($guaranteeID);
		$result = ['result'=>'','msg'=>''];
		if(!empty($guarantee)){
			$guaranteeStatus = $guarantee->status;
			if($guaranteeStatus == UserGuaranteeDeal::GUARANTEE_IN){
				$payResult = self::payGold($appendGold, $guarantee->resource, $guarantee->resource_id, $userID, $appendReason);
				if($payResult['result'] == 1){//支出成功
					//更新剩余担保金额
					$guarantee->remain_gold = $guarantee->remain_gold + $appendGold;
					$guarantee->save();
					//生成担保操作记录
					self::addGuaranteeRecord($userID, $guaranteeID, $payResult['deal_id'], 1, $appendReason);
					$result['result'] = 1;
					$result['guarantee_id'] = $guaranteeID;
					$result['current_gold'] = $payResult['current_gold'];
					
				}else{//支出失败
					$result['result'] = 0;
					$result['guarantee_id'] = 0;
					$result['current_gold'] = $payResult['current_gold'];
					$result['msg'] = $payResult['msg'];
				}
			}else{//担保结束
				$result['result'] = 0;
				$result['guarantee_id'] = $guaranteeID;
				$result['msg'] = '担保已结束';
			}
		}else{//担保不存在
			$result['result'] = 0;
			$result['guarantee_id'] = $guaranteeID;
			$result['msg'] = '担保不存在';
		}
		return $result;
	}
	
	/**
	 * 担保交易支付接口
	 * @param unknown $guarantee 担保,也可以是担保id，如果传入数字则按照担保id处理
	 * @param unknown $payGold     支付金币数
	 * @param unknown $payReason   支付备注
	 * @param unknown $toUserID	          目标用户ID
	 * @return array $result['result']:结果，1：成功 0：失败  $result['guarantee_id'] :担保id, $result['msg']:错误信息
	 */
	public static function guaranteeDealPay($guarantee,$payGold,$toUserID,$payReason='')
	{
        if(is_numeric($guarantee)){
            $guarantee = UserGuaranteeDeal::find($guarantee);
        }
        $guaranteeID = $guarantee->id;
        $result = ['result'=>'','msg'=>''];
		if(!empty($guarantee)){
			$guaranteeStatus = $guarantee->status;
			if($guaranteeStatus == UserGuaranteeDeal::GUARANTEE_IN){
				$user = User::find($toUserID);
				//判断用户是否存在(userid=0 表示返还系统）
				if(!empty($user) || $toUserID == 0){
					//担保金额是否充足
					if($guarantee->remain_gold >= $payGold){
						$guarantee->remain_gold = $guarantee->remain_gold - $payGold;
						$guarantee->save();
						//生成目标用户收入的金币记录
						$incomeResult = self::incomeGold($payGold, $guarantee->resource, $guarantee->resource_id, $toUserID, $payReason);
						//生成担保操作记录
						self::addGuaranteeRecord($toUserID, $guaranteeID, $incomeResult['deal_id'], 2, $payReason);
						$result['result'] = 1;
						$result['guarantee_id'] = $guaranteeID;
					}else{
						$result['result'] = 0;
						$result['guarantee_id'] = $guaranteeID;
						$result['msg'] = '担保金额不足';
					}
				}else{
					$result['result'] = 0;
					$result['guarantee_id'] = $guaranteeID;
					$result['msg'] = '目标用户不存在';
				}
			}else{//担保结束
				$result['result'] = 0;
				$result['guarantee_id'] = $guaranteeID;
				$result['msg'] = '担保已结束';
			}
		}else{//担保不存在
			$result['result'] = 0;
			$result['guarantee_id'] = $guaranteeID;
			$result['msg'] = '担保不存在';
		}
		return $result;
	}
	
	/**
	 * 担保交易完成接口
	 * @param unknown $guarantee  担保id
	 * @param unknown $finishRemark 完成备注
	 * @param unknown $status		结果2:交易成功，3：交易失败
	 * @return multitype:string number unknown
	 */
	public static function finishGuaranteeDeal($guarantee, $status, $finishRemark=''){
        if(is_numeric($guarantee)){
            $guarantee = UserGuaranteeDeal::find($guarantee);
        }
        $guaranteeID = $guarantee->id;
		$result = ['result'=>'','msg'=>''];
		if(!empty($guarantee)){
			$guaranteeStatus = $guarantee->status;
			if($guaranteeStatus == UserGuaranteeDeal::GUARANTEE_IN){
				//判断担保余额
				if($guarantee->remain_gold == 0){
					//更新担保转态
					$guarantee->status = $status;
					$guarantee->save();
					$result['result'] = 1;
					$result['guarantee_id'] = $guaranteeID;
				}else{
					//退还担保用户剩余的金币
					$incomeResult = self::incomeGold($guarantee->remain_gold, $guarantee->resource, $guarantee->resource_id, $guarantee->user_id, $finishRemark);
					self::addGuaranteeRecord($guarantee->user_id, $guaranteeID, $incomeResult['deal_id'], 3, $finishRemark);
					//更新担保转态
					$guarantee->remain_gold = 0;
					$guarantee->status = $status;
					$guarantee->save();
					$result['result'] = 1;
					$result['guarantee_id'] = $guaranteeID;
				}
				
			}else{//担保结束
				$result['result'] = 0;
				$result['guarantee_id'] = $guaranteeID;
				$result['msg'] = '担保已结束';
			}
		}else{//担保不存在
			$result['result'] = 0;
			$result['guarantee_id'] = $guaranteeID;
			$result['msg'] = '担保不存在';
		}
		return $result;
	}
	
	/**
	 * 添加金币记录
	 * @param unknown $userID
	 * @param unknown $incomeType
	 * @param unknown $resourceID
	 * @param unknown $type
	 * @param unknown $gold
	 * @param unknown $remark
	 * @param unknown $result
	 */
	public static function addUserRecord($userID,$incomeType,$resourceID,$type,$gold,$result,$remark)
	{
		if($type == 'pay'){
			$gold = -$gold;
		}
		$goldRecord = UserGoldRecord::create([
			'user_id' => $userID,
			'resource' => $incomeType,
			'resource_id' => $resourceID,
			'type' => $type,
			'gold' => $gold,
			'result' => $result,
			'remark' => $remark,
		]);
		return $goldRecord->id;
	}
	
	/**
	 * 担保交易 记录
	 * @param unknown $userID
	 * @param unknown $gold
	 * @param unknown $guaranteeType
	 * @param unknown $guaranteeID
	 * @param number $status
	 */
	public static function addGuaranteeDeal($userID,$gold,$guaranteeType,$guaranteeID,$status=1)
	{
		$guarantee = UserGuaranteeDeal::create([
			'user_id' => $userID,
			'initial_gold' => $gold,
			'remain_gold' => $gold,
			'resource' => $guaranteeType,
			'resource_id' => $guaranteeID,
			'status' => $status
		]);
		return $guarantee->id;
	}
	
	/**
	 * 担保交易操作记录
	 * @param unknown $userID
	 * @param unknown $guaranteeID
	 * @param unknown $goldID
	 * @param unknown $action
	 * @param unknown $remark
	 */
	public static function addGuaranteeRecord($userID,$guaranteeID,$goldID,$action,$remark='')
	{
		$guaranteeRecord = UserGuaranteeRecord::create([
			'user_id' => $userID,
			'guarantee_id' => $guaranteeID,
			'gold_id' => $goldID,
			'action' => $action,
			'remark' => $remark
		]);
		return $guaranteeRecord->id;
	}
    public static function findGuaranteeId($resourceType, $resourceId){
        $guarantee = self::findGuarantee($resourceType, $resourceId);
        if(is_null($guarantee)){
            return 0;
        }
        return $guarantee->id;
    }
    public static function findGuarantee($resourceType, $resourceId){
        $resource = '';
        if($resourceType == 'task'){
            $resource = '5000123';
        }else{
        $resource = $resourceType;
        }

        $guarantee = UserGuaranteeDeal::where('resource',$resource)
            ->where('resource_id',$resourceId)->first();
        return $guarantee;
    }
	/**
	 * 内容发布金币支出接口
	 * @param unknown $resource 交易类型
	 * @param unknown $resourceID 关联id , 由于检查在发布之前，因此此值为0，仅作备用
	 * @param unknown $userID 用户
	 * @param unknown $remark 备注,发布内容的说明
	 * @return boolean 发布成功或者失败
	 */
    public static function publishPayGold($resource,$resourceId,$userId,$remark=''){
        $moneyMap = [
            'ip'                =>[ 'code'  =>'5000111', 'money' => 200 ],  // '发布作品',
            'ip_scene'          =>[ 'code'  =>'5000113', 'money' => 100 ],  // '发布场景',
            'ip_dialogue'       =>[ 'code'  =>'5000113', 'money' => 100 ],  // '发布台词',
            'ip_role'           =>[ 'code'  =>'5000129', 'money' => 100 ],  // '发布角色',
            'user_production'   =>[ 'code'  =>'5000128', 'money' => 100 ],  // '发布用户作品',
            'dimension'         =>[ 'code'  =>'5000115', 'money' => 200 ],  // '创建次元',
            'dimension_publish' =>[ 'code'  =>'5000126', 'money' => 50  ],   // '发布次元帖子',
            'discussion'        =>[ 'code'  =>'5000127', 'money' => 5   ],    // '发表评论',
        ];
        $payGold = $moneyMap[$resource]['money'];
        $payType = $moneyMap[$resource]['code'];
        $res = self::payGold($payGold, $payType, $resourceId, $userId, $remark);
        return $res['result'] == 1;
    }
}
