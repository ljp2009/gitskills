<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Common\CommonUtils as CU;
use App\Common\Image;
class DimensionPublish extends Model
{
    protected $table = 't_dimension_publish';
    protected $guarded = ['id'];
    use SoftDeletes;
	public function user(){
    	return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
	public function dimension(){
    	return $this->hasOne('App\Models\Dimension', 'id', 'dimension_id');
    }
    public function getImageAttribute($value) {
        return Image::makeImages($value);
    }
    public function getCoverAttribute($value) {
        if(count($this->image)>0){
            return $this->image[0];
        }else{
            return Image::makeImage('');
        }
    }
    public function getDetailUrlAttribute() {
    	return '/dimpub/'.$this->id;
    }
}
