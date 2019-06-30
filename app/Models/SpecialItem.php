<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\Image;
class SpecialItem extends Model
{
    protected $table     = 't_special_item';
    protected $guarded   = ['id'];
    public function getImgAttribute($value){
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
                return $this->url;
  		}
  	}
  	/**
  	 * 获取推荐作品的标签分类
  	 * @return string
  	 */
  	public function getTagAttribute(){
  		$tag = '';
  		switch ($this->type){
  			case 'game':
  				$tag = '游戏';
  				break;
  			case 'cartoon':
  				$tag = '动漫';
  				break;
  			case 'story':
  				$tag = '小说';
  				break;
  			case 'coll':
  				$tag = '同人';
  				break;
  			case 'peri':
  				$tag = '周边';
  				break;
  			case 'disc':
  				$tag = '长评';
  				break;
  			case 'original':
  				$tag = '原创';
  				break;
  		}
  		return $tag;
  	}
}
