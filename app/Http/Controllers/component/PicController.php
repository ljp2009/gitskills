<?php

namespace App\Http\Controllers\Component;
use Storage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\CommonUtils;
use Publish, Input;

class PicController extends Controller{
    /*
     * 请把php.ini里upload_tmp_dir 项的值改为storage/app/temp的绝对路径
     */

    private $uptypes = array(  
        'image/jpg'=>'jpg',  
        'image/jpeg'=>'jpg',   
        'image/png'=>'png',  
        'image/pjpeg'=>'jpg',   
        'image/gif'=>'gif',  
        'image/bmp'=>'bmp', 
        'image/x-png'=>'png'   
    );  
      
    private $max_file_size=10485760 ; //10MB
    private $destination_folder="/uploadimg/"; //上传文件路径   

    private function composeImageReturnInfo($issuccess, $images, $errorMessage=''){
        $message = '{"success":'.($issuccess?1:0).',';
        if($issuccess){
            if(!is_array($images)){
                $images = array($images);
            }
            $message.='"images":[';
            $ct = 0; $sz = sizeof($images);
            foreach($images as $img){
                $ct ++;
                $message.='"'.$img.'"';
                if($ct<$sz){
                    $message.=',';
                }
            }
            $message.=']';
        }else{
            $message.= '"errorMessage":"'.$errorMessage.'"';
        }
        return $message.'}';
    }

    private function getTempStoragePath(){
        return 'temp/';
    }

    private function uploadOneImage($file, $ext){
        if($file->isValid()){
            $clientName = $file->getClientOriginalName();
            $tmpName = $file->getFileName();
            $picname = CommonUtils::createRandomId('pic');
            $newfilename = $picname.'.'.$ext;
            //$path = $file->move($this->getImageUploadPath(), $newfilename); //TODO More flexible
            Storage::put('avatars/'.$newfilename, Storage::disk('local')->get($this->getTempStoragePath().$tmpName));
            $path =self::composeImageLocation('avatar', $picname, $ext);
            return $path;
        }
        return '';
  
    }
    public function uploadImage(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
            $mode = Input::get("uploadmode");
            if($mode == 'single'){
                $file = Input::file('singlefile');
                if($this->max_file_size < $file->getSize()){  
                    return $this->composeImageReturnInfo(false, array(), '上传的图片不允许大于10MB！');
                }  
                    
                if(!array_key_exists($file->getMimeType(), $this->uptypes)) {  
                    return $this->composeImageReturnInfo(false, array(), '上传图片类型不允许(jpg, png, bmp, gif)！');
                }   
                $images = array();   
                $result = $this->uploadOneImage($file, $this->uptypes[$file->getMimeType()]);
                if(strlen($result) > 0){
                    $images[0] = $result;
                    return $this->composeImageReturnInfo(true, $images);
                }else{
                    return $this->composeImageReturnInfo(false, array(), '上传图片失败！');
                }           
            }else{
                $files = Input::file("files"); 
                foreach ($files as $file) {
                    if($this->max_file_size < $file->getSize()){  
                        return $this->composeImageReturnInfo(false, array(), '上传的图片不允许大于10MB！');
                    }  
                        
                    if(!array_key_exists($file->getMimeType(), $this->uptypes)) {  
                        return $this->composeImageReturnInfo(false, array(), '上传图片类型不允许(jpg, png, bmp, gif)！');
                    }                 
                }
                $count = 0; 
                
                $images = array();
                foreach ($files as $file) {
                    $result = $this->uploadOneImage($file, $this->uptypes[$file->getMimeType()]);
                    if(strlen($result)>0){
                        $images[$count] = $result;
                        $count ++; 
                    }
                }
                return $this->composeImageReturnInfo(true, $images);
             
            }
         }
    }

    public static function delOneImage($imageURL){
        $imageURL = substr($imageURL, strlen('/pic/'));
        Storage::delete(self::evalImageLocation($imageURL));
    }
 
    private static function composeImageLocation($type, $name, $ext=''){
        if(strlen($ext) == 0){
            return '/pic/'.$type.'_'.$name;
        }else{
            return '/pic/'.$type.'_'.$name.'_'.$ext;
        }
    }
    private static function evalImageLocation($imageURL){
        $picSets = explode('_', $imageURL);
        $type = $picSets[0];
        $name = $picSets[1];
        $ext = '.jpg';
        if(sizeof($picSets) >=3){
            $ext = '.'.$picSets[2];
        }
        $path = '';
        switch ($type) {
            case 'cover':
            case 'colleague':
                $path = 'covers/'.$name.$ext;
                break;
            case 'scene':
            case 'peripheral':
                $path = 'pics/'.$name.$ext;
                break;
            case 'role':
                $path = 'roleheaders/'.$name.$ext;
                break;
            case 'avatar':
            case 'header':
                $path = 'avatars/'.$name.$ext;
                break;
            default:
                $path = 'pics/'.$name.$ext;
                break;

        }       
        return $path;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return Storage::get(self::evalImageLocation($id));
    }

    public function localPic($folder, $picname){
        return Storage::disk('local')->get($folder.'/'.$picname);
    }
}
