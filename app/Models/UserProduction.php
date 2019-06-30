<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Common\OwnerHandler;
use App\Models\LikeModel;
use App\Models\Discussion;
use App\Models\ProductionContent;
use App\Models\Verifiedable;
use Auth;
class UserProduction extends Model
{
    protected $table = 't_user_production';
  	protected $guarded = ['id'];
    use SoftDeletes;
    use Verifiedable;
    /* Attributes */
    // production title 
    public function getTitleAttribute(){
        $title = ($this->is_original?'【原创】':'【转载】').$this->name;
        return $title;
    }
    public function setTitleAttribute($value){
        $this->attributes['name'] = $value;
    }

    //cover
    public function getCoverAttribute(){
        $imgs = Image::makeImages($this->image);
        if(count($imgs)>0){
            return $imgs[0];
        }
        else{
            return Image::makeImage('');
        }
    }
    public function getImagesAttribute(){
        $imgs = Image::makeImages($this->image);
        return $imgs;
    }
    public function setCoverAttribute($value){
        $this->attributes['image'] = $value;
    }
    //time
    public function getTimeAttribute(){
        $this->created_at;
    }
    //introText
    public function getIntroTextAttribute(){
        return mb_substr($this->attributes['intro'], 0, 100,'utf-8');
    }
    //is_original(use default attribute set)

    /* suminfo */
    //like sum(use default attribute set)
    //discussion sum
    public function getDiscCountAttribute(){
        return Discussion::countDiscuss('user_production', $this->id);
    }   
    /* related */
    //ip
    public function ip(){
        return $this->hasOne('App\Models\Ip','id','ip_id');
    }
    public function getLabelAttribute(){
        switch($this->relate_type){
        case "coll":
            return '同人';
        case "peri":
            return '周边';
        case "disc":
            return '长评';
        default:
            return '作品';
        }
    }
    public function getTypeLabelAttribute(){
        return $this->label;
    }
    //user 
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function getFormatTextAttribute(){
        return '<p>'.str_replace("\n", "</p><p>", $this->intro).'</p>';
    }
    public function getDetailUrlAttribute(){
        return '/user/product/'.$this->id;
    }
    public function contents(){
        return $this->hasMany('App\Models\ProductionContent','production_id','id')->orderBy('order');
    }
    public function checkUserLike(){
        if(!Auth::check())return false;
        $like = LikeModel::where('resource_id', $this->id)
                ->where('resource','user_production')
                ->where('user_id', Auth::user()->id)->first();
        return !is_null($like);
    }
    public function getDiscCount(){
        return Discussion::countDiscuss('user_production', $this->id);
    }
    public function getLinkAttribute($value){
    	$link = json_decode($value,true);
    	if(isset($link['link']) && isset($link['show'])){
    		return $link;
    	}
    	return null;
    }
    public function getLinkA($class=''){
        $link = $this->link;
        $url = is_null($link)?'javascript:void(0)':$link['link'];
        $text = is_null($link)?'':$link['show'];
 
        return "<a class='$class' href='$url'>$text</a>";
    }
    public function getShotIntro($length){
       return mb_substr($this->attributes['intro'], 0, $length,'utf-8');
    }
    public function checkOwner(){
        return OwnerHandler::checkByObj('user_production', $this);
    }
    /*将内容转换为图文混排模式
     * @mode 转换方式，
     *      0：如果已经有混排内容则不转换
     *      1：如果已经有混排内容则将现有内容追加到已有内容后面
     *      2：移除所有已有混排内容后将新的内容插入
     * @return void
     */
    public function convertContent($mode = 0){
        $ctCount = count($this->contents);
        if($ctCount > 0 && $mode == 0){
            return 0;
        }
        if($mode = 2 && $ctCount > 0){
            ProductionContent::where('production_id', $this->id)->delete();
            $ctCount = 0;
        }
        $ctCount = $this->convertTextContent($ctCount);
        $ctCount = $this->convertImageContent($ctCount);
        $ctCount = $this->convertLinkContent($ctCount);
        return $ctCount;
    }
    private function convertTextContent($order){
        $text = $this->intro;
        if(trim($text) == ''){
            return $order;
        }
        $pc = new ProductionContent;
        $pc->type = 'text';
        $pc->text = $text;
        $pc->order = $order + 1;
        $pc->production_id = $this->id;
        $pc->status = 0;
        $pc->save();
        return $order + 1;

    }
    private function convertImageContent($order){
        $imgs = $this->images;
        $fromOrder = $order;
        foreach($imgs as $img){
            $fromOrder  += 1;
            $pc = new ProductionContent;
            $pc->type = 'image';
            $pc->text = '';
            $pc->url  = $img->getPath();
            $pc->order = $fromOrder;
            $pc->production_id = $this->id;
            $pc->status = 0;
            $pc->save();
        }
        return $fromOrder;
    }
    private function convertLinkContent($order){
        $link = $this->link;
        if(is_null($link)){
            return $order;
        }
        $pc = new ProductionContent;
        $pc->type = 'link';
        $pc->text = $link['show'];
        $pc->url  = $link['link'];
        $pc->order = $order + 1;
        $pc->production_id = $this->id;
        $pc->status = 0;
        $pc->save();
        return $order + 1;
    }
    /*
  	public function getProductAttr(){
  		return $this->hasOne('App\Models\SysAttrEnum','code','attr_code');
  	}
    public function sellInfo(){
        return $this->hasOne('App\Models\UserProdSellInfo','prod_id','id');
    }
    public function getPriceAttribute(){
        if(!is_null($this->sellInfo)){
            return $this->sellInfo->price;
        }
        return '';
    }
    public function getsellIntroAttribute(){
        if(!is_null($this->sellInfo)){
            return $this->sellInfo->sell_intro;
        }
        return '';
    }
    public function getRelatedTypeLabelAttribute(){
        switch($this->relate_type) {
            case 'coll':
                return '同人作品';
            case 'peri':
                return '周边产品';
            case 'disc':
                return '长评论';
        }
        return '作品';
    }
    public function getTypeLabelAttribute(){
        switch($this->relate_type) {
            case 'coll':
                return "同人";
            case 'peri':
                return "周边";
            case 'disc':
                return "长评论";
        }
        return "作品";
    }
    public function getUrlAttribute(){
        return '/user/product/'.$this->id;
    }
    public function getTitleAttribute(){
        return $this->name;
    }
    public function getFormatTextAttribute(){
        return '<p>'.str_replace("\n", "</p><p>", $this->intro).'</p>';
    }
	public function getShotTitle($length){
    	$str_cut = $this->title;
    	if(mb_strlen($str_cut) > $length){
  			$str_cut = mb_substr($str_cut,0,$length)."..";
  		}else{
  			$str_cut = $str_cut;
  		}
    	return $str_cut;
    }
    public function getOwnerIsMeAttribute(){
        return Auth::check()?($this->user_id==Auth::user()->id):false;
    }
    public function getLikeCount(){
        $likeSum = LikeSumModel::where('resource', 'user_production')
            ->where('resource_id', $this->id)->first();
        if(is_null($likeSum)) return 0;
        return $likeSum->like_sum;
    }
     */
}
