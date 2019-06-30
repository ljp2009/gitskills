<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\LikeModel;
use App\Models\Discussion;

class ActivityPartner extends Model {

    protected $table = 't_activity_partner';
    protected $guarded = ['id'];

//    public function getImageAttribute($value) {
//        //return CU::getImagePaths('Activity', 'image', $value);
//        return Image::makeImages($value);
//    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function ProDim() {
        if ($this->resource == 'user_production') {
            return $this->hasOne('App\Models\UserProduction', 'id', 'resource_id');
        } elseif ($this->resource == 'dimension_publish') {
            return $this->hasOne('App\Models\DimensionPublish', 'id', 'resource_id');
        }
        else {
            return null;
        }
    }
    public function getCoverAttribute(){
        return $this->ProDim->cover;
    }
    public function getDetailUrlAttribute(){
        return $this->ProDim->detailUrl;
    }

    public function getVotedAttribute(){
        return LikeModel::CheckLike($this->resource, $this->resource_id);
    }

    public function getVoteCountAttribute(){
        return $this->ProDim->like_sum;
    }

    public function getDiscussionCountAttribute(){
        return LikeModel::countDiscuss($this->resource, $this->resource_id);
    }

}
