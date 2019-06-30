<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Models\UserProduction;
use App\Models\Ip;

class UmeiiiRecommend extends Model
{
    protected $table = 't_umeiii_recommend';
  	protected $guarded = ['id'];
  	public function getImageAttribute($value){
  		return Image::makeImage($value);
  	}

  	/**
  	 * 获取推荐的作品的详情地址
  	 * @return string
  	 */
  	public function getUrlAttribute(){
  		switch ($this->type){
  			case 'game':
  			case 'cartoon':
  			case 'story':
  				return '/ip/'.$this->resource_id;
  			case 'coll':
  			case 'peri':
  			case 'disc':
  			case 'original':
  				return '/user/product/'.$this->resource_id;
            default:
  				return '/user/product/'.$this->resource_id;
  		}
  	}
    public function user(){
		return $this->hasOne('App\Models\User', 'id', 'creator');
    }
  	/**
  	 * 获取推荐作品的标签分类
  	 * @return string
  	 */
  	public function getTagAttribute(){
  		$tag = '';
  		switch ($this->type){
  			case 'game':
  				$tag = '游 戏';
  				break;
  			case 'cartoon':
  				$tag = '动 漫';
  				break;
  			case 'story':
  				$tag = '小 说';
  				break;
  			case 'light':
  				$tag = '轻小说';
  				break;
  			case 'coll':
  				$tag = '同 人';
  				break;
  			case 'peri':
  				$tag = '周 边';
  				break;
  			case 'disc':
  				$tag = '长评论';
  				break;
  			case 'original':
  				$tag = '原创';
  				break;
            default:
  				$tag = '作品';
  				break;
  		}
  		return $tag;
  	}
    public function batchInfo(){
        return $this->belongTo('App\Models\UmeiiiRecommendBatch','id','batch_id');
    }
    public function getAuthorAttribute(){
  		switch ($this->type){
  			case 'game':
  			case 'cartoon':
  			case 'story':
                $ip = Ip::find($this->resource_id);
                return is_null($ip)?'<已删除>':$ip->authorAttr;
  			case 'coll':
  			case 'peri':
  			case 'disc':
  			case 'original':
                $userProd = UserProduction::find($this->resource_id);
                return is_null($userProd)?'<已删除>':$userProd->user->display_name;
            default:
                return '';
  		}
    }
}
