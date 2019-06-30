<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\SimpleHTMLDOM;
use App\Models as MD;
use Input, Utils, Storage, Redirect, Auth;

class BaiduImportController extends Controller
{
    public static $MESSAGE_FILE = "messageFile.txt";
    public static $SELECTED_FILE = "selectFile.txt";
    private static $SEP = "@@";
    private static $IMG_BLOCK = "**IMG**";
    private $timeout = 60;
    private $md5v= '';
    private $host = '';

    private $ignoreBlocks = array('script'=>'script', 
        'style'=>'style');
    private $nonewlineBlocks = array('a'=>'a', 'span'=>'span', 'em'=>'em', 'i'=>'i');

    public function postSave(){
        $data = $this->attachPrevValues(array(), false);

        $userId = Auth::user()->id;
        $ip = MD\Ip::create(['name'=>$data['zpm'], 'type'=>'story', 'cover'=>$data['fmt'], 'creator'=>$userId ]);
        $ipid = $ip->id;

        MD\IpIntro::create(['ip_id'=>$ipid, 'intro'=>$data['nrms']]);

        $this->saveAttrs($ipid, $data['jbxx'], $data['functype']);

        $hero = MD\IpRole::create(['ip_id'=>$ipid, 
            'name'=>$data['zj'],
            'header'=>$data['zjt'],
            'intro'=>$data['zjms'], 
            'creator'=>$userId,
            'mender'=>$userId]);   

        MD\IpContributor::create(['ip_id'=>$ipid, 'user_id'=>$userId, 
            'type'=>'role','obj_id'=>$hero->id]);  


        return Redirect::to('/ip/'.$ipid);
    }

    private function saveAttrs($ipid, $jbxx, $functype){
         $sysAttrs = $this->attachBasicAttrs($functype)['sysAttr'];
         $sysnew = array();
         foreach($sysAttrs as $oneattr){
            $sysnew[$oneattr['name']] = $oneattr['code'];
         }
         if(strlen($jbxx)==0){
            return;
         }
         $ajxs = explode(";", $jbxx);
         foreach($ajxs as $ajx){
            if(strlen($ajx)==0){
                continue;
            }
            $cv = explode(":", $ajx);
            if(sizeof($cv)<2)
                continue;
            $name = $cv[0];
            $value = $cv[1];
            if(array_key_exists($name, $sysnew)){
                $code = $sysnew[$name];
                MD\IpAttr::create(['ip_id'=>$ipid, 'code'=>$code, 'value'=>$value]);
            }
         }
    }

    private function isExists($md5v){
        if(!Storage::disk('local')->exists($this->getTempStoragePath())){
            Storage::disk('local')->makeDirectory($this->getTempStoragePath());
        }
        return Storage::disk('local')->exists($this->getTempStoragePath().$md5v.'/'.self::$MESSAGE_FILE);
    }

    private function getStoragePath( $autocreate=true){
        $spath = $this->getCustomizedFolderPath();
        $path = storage_path().'/app/'.$spath;
        if(!Storage::disk('local')->exists($spath)){
            if($autocreate){
                Storage::disk('local')->makeDirectory($spath);
            }
        }
        return $path;
    }

    private function removePath(){
        $spath = $this->getCustomizedFolderPath();
        if(Storage::disk('local')->exists($spath))
            Storage::disk('local')->deleteDirectory($spath);
        return true;
    }

    private function getTempStoragePath(){
        return 'temp/baiduimport/';
    }

    private function getCustomizedFolderPath(){
        return $this->getTempStoragePath().$this->md5v.'/';
    }

    public function displaySearch(){
        return view('common.baidusearch');
    }

    public function attachSearch($type=''){
        return view('common.baidusearch', $this->attachPrevValues(array('type'=>$type), false));
    }

    private function getHTMLContentFromURL($url, $agent=false){
           $this->evalHost($url);
           $nw=urlencode($url);
           $ch=curl_init();
           curl_setopt($ch,CURLOPT_URL,$url);
           curl_setopt($ch,CURLOPT_HEADER,0);
           curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
           curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
           curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
           $myagent = "Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4";

           if($agent)
                curl_setopt($ch, CURLOPT_USERAGENT, $myagent);
            else
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
           $output=curl_exec($ch);
           curl_close($ch);
           return $output;
    }

    private function downloadImageFromBaidu($url) {    
        $fp = fopen($url, 'r') or exit('Open url faild!');    
        if($fp){  
            $file = '';
            while(!feof($fp)) {    
                $file.=fgets($fp)."";  
            }  
            fclose($fp); 
            $temppicName = Utils::createRandomId().'.jpg';
            $imageloc = $this->getStoragePath().$temppicName;
            file_put_contents($imageloc, $file);
            return $temppicName;
        }   
        return '';
    }  
    private function downloadImageFromBaidu0($url){
        $url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url ); 
        if (!extension_loaded('sockets')) 
            return false; 
        preg_match( '/http:\/\/([^\/\:]+(\:\d{1,5})?)(.*)/i', $url, $matches ); 
        if (!$matches) 
            return false; 
        $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP ); 
        if ( !@socket_connect( $sock, $matches[1], $matches[2] ? substr($matches[2], 1 ) : 80 ) ) { 
            return false; 
        } 

        $msg = 'GET ' . $matches[3] . " HTTP/1.1\r\n"; 
        $msg .= 'Host: ' . $matches[1] . "\r\n"; 
        $msg .= 'Connection: Close' . "\r\n\r\n"; 
        socket_write( $sock, $msg ); 
        $bin = ''; 
        while ( $tmp = socket_read( $sock, 10 ) ) { 
            $bin .= $tmp; 
            $tmp = ''; 
        } 
        $bin = explode("\r\n\r\n", $bin); 
        $img = $bin[1]; 
        $temppicName = Utils::createRandomId().'.jpg';
        $imageloc = $this->getStoragePath().$temppicName;
        $h = fopen( $imageloc, 'wb' ); 
        $res = fwrite( $h, $img ) === false ? false : true; 
        @socket_close( $sock ); 
        return $res; 

    }

    private function getElementAttrValue($elehtml, $attr){
        $src = $attr.'=';
        $mark = '"';
        $srcre = '';
        $pos1 = strpos($elehtml, $src.$mark);
        $pos2 = strpos($elehtml, $src.'\'');
        if($pos1>0){
            $str = substr($elehtml, $pos1 + strlen($src) + 1);
            $srcre = substr($str, 0, strpos($str, $mark));
        }elseif($pos2>0){
            $str = substr($elehtml, $pos2 + strlen($src) + 1);
            $srcre = substr($str, 0, strpos($str, '\''));
        }else{
            return '';
        }
        return $srcre;       
    }

    private function getSrcFromImageEle($imghtml){
        $src = 'src=';
        $srcre = $this->getElementAttrValue($imghtml, 'src');
        if(strlen($srcre) > 0){
            $srcpos = strpos($srcre, $src);
            if($srcpos>0){
               //$srcre = substr($srcre, $srcpos + strlen($src));
            }
            if(strpos($srcre, '/')==0){
                return $this->host.$srcre;
            }
        }
        return $srcre;
    }

    private function getHrefFromA($ahtml){
        $src = 'href=';
        $srcre = $this->getElementAttrValue($ahtml, 'href');
        if(strlen($srcre) > 0){
            if(strpos($srcre, '/')==0){
                return $this->host.$srcre;
            }
        }
        return $srcre;       
    }



    private function getMinPos($pos1, $pos2, $pos3, $content){
        $leng = strlen($content);
        $pos1 = (($pos1<0)?$leng:$pos1);
        $pos2 = (($pos2<0)?$leng:$pos2);
        $pos3 = (($pos3<0)?$leng:$pos3);
        $min = ($pos1<$pos2?$pos1:$pos2);
        $min = ($min<$pos3?$min:$pos3);
        return $min;
    }

    private function locatePos($ele, $content){
        $ele1 = $ele.' ';
        $ele2 = $ele.'>';
        $ele3 = $ele.'/>';
        $pos1 = $this->getStrPos($content, $ele1);
        $pos2 = $this->getStrPos($content, $ele2);
        $pos3 = $this->getStrPos($content, $ele3);  
        $targetpos = $this->getMinPos($pos1, $pos2, $pos3, $content);
        return $targetpos;
    }

    private function isStartsWithAnAtomicEle($content){
        $leng = strlen($content);
        $pos1 = $this->getStrPos($content, '>');
        $pos2 = $this->getStrPos($content, '/>');
        if($pos1<0)
            return false;
        if($pos2<0)
            $pos2 = $leng;
        return $pos2 < $pos1;
    }

    private function getStrPos($str, $fr){
         $pos = strpos($str, $fr);
         if($pos !== false){
            return $pos;
         }else{
            return -1;
         }
    }

    private function findFirstElementIn($elename, $content, $atomic=false){
        $result = array();
        $ele = '<'.$elename;
        $ele3 = $ele.'/>';
        $endele = '</'.$elename.'>';
        $pos = $this->getStrPos($content, $ele);
        if($pos<0)
            return $result;

        $targetpos = $this->locatePos($ele, $content);
        if($targetpos<0)
            return $result;
        $content = substr($content, $targetpos);
        if($this->isStartsWithAnAtomicEle($content)){
            $atomic = true;
        }       
        if($atomic){
             $nextpos = $this->getStrPos($content, '>');
            if($nextpos<0){
                return array('element'=>$content, 'rest'=>'');
            }else{
                return array('element'=>substr($content, 0, $nextpos+1), 'rest'=>substr($content, $nextpos+1));
            }
        }else{
            $ct = 1;
            $tempcontent = substr($content, strlen($ele));
            $head = $ele;
            while(true){
                 $endpos = $this->getStrPos($tempcontent, $endele);
                 $targetpos = $this->locatePos($ele, $tempcontent);
                 if($targetpos < 0){
                    if($endpos<0){
                        return array('element'=>$content, 'rest'=>'');
                    }else{
                        $tail = substr($tempcontent, 0, $endpos + strlen($endele));
                        $rest = substr($tempcontent, $endpos + strlen($endele));
                        return array('element'=>$head.$tail, 'rest'=>$rest);
                    }
                 }else{
                    if($endpos<0){
                        return array('element'=>$content, 'rest'=>'');
                    }else{
                        if($targetpos < $endpos){
                            $ct ++;
                            $head .= substr($tempcontent, 0, $targetpos + strlen($ele));
                            $tempcontent = substr($tempcontent, $targetpos+strlen($ele));
                        }else{
                            $ct --;

                            $head .= substr($tempcontent, 0, $endpos + strlen($endele));
                            $tempcontent = substr($tempcontent, $endpos + strlen($endele));
                            if($ct == 0){
                                break;
                            }
                        }
                    }
                 }
            }
            return array('element'=>$head, 'rest'=>$tempcontent);
        }

    }

    private function findAllElementIn($elename, $content, $feature='', $atomic=false){
        $array = array();
        $ele = '<'.$elename;

        if(strlen($feature)==0){
            $feature = $ele;
        }
        $pos = $this->getStrPos($content, $feature);
        if($pos<0)
            return $array;
        $content = substr($content, $pos);
        while(true){
            $result = $this->findFirstElementIn($elename, $content, $atomic);
            if(sizeof($result)==0){
                break;
            }else{
                $content = $result['rest'];
                array_push($array, $result['element']);
                if(strlen($content)==0){
                    break;
                }
            }
        }
        return $array;
    }

    private function loadContentFromFile($filename){
        $filepath = $this->getCustomizedFolderPath().$filename;
        if(Storage::disk('local')->exists($filepath))
            return Storage::disk('local')->get($filepath);
        return '';
    }

    private function putContentToFile($content, $filename='', $ext='txt'){
        if(strlen($filename)==''){
            $filename = Utils::createRandomId().'.'.$ext;
        }
        $filepath = $this->getCustomizedFolderPath().$filename;
        Storage::disk('local')->put($filepath, $content);
        return $filename;
    }

    private function parseHTMLContentSubprocess($url){
        $content = $this->getHTMLContentFromURL($url, false);
        $mainobjf = '<div id="main"';
        $validcontent = $this->findAllElementIn('div', $content, $mainobjf);
        $content = $validcontent[0];
        $leftfull = '余下全文'; 
        if($this->getStrPos($content, $leftfull)>=0){
            $as = $this->findAllElementIn('a', $content);
            
            for($i=sizeof($as)-1; $i>=0; $i--){
                $onea = $as[$i];
                $pos = $this->getStrPos($onea, $leftfull);
                if($pos>=0){
                    return array('next'=>$this->getHrefFromA($onea), 
                        'content'=>$content);
                }
            }           
        }

        return array('next'=>'', 
                        'content'=>$content);
    }

    private function loadMessageTxt(){
        $filecontent = $this->loadContentFromFile(self::$MESSAGE_FILE);
        $filecs = explode(self::$IMG_BLOCK, $filecontent);
        $contentblock = explode(self::$SEP, $filecs[0]);
        if(sizeof($filecs)<2||strlen(trim($filecs[1]))==0){
            $images = array();
        }else{
            $images = explode(self::$SEP, $filecs[1]);
        }
        return array('contentblock'=>$contentblock, 
            'images'=>$images,  'md5v'=>$this->md5v, 'record'=>$this->loadContentFromFile(self::$SELECTED_FILE));
    }

    private function saveMessageTxt($contentblock, $images){
        $messagefile = $this->getCustomizedFolderPath().self::$MESSAGE_FILE;
        $content = '';

        foreach($contentblock as $oneblock){
            $content.=$oneblock.self::$SEP;
        }
        $content.= self::$IMG_BLOCK;
        foreach($images as $img){
            $content.=$img.self::$SEP;
        }

        Storage::put($messagefile, $content);
    }

    private function dealIgnoreBlock($content, $ignoreele){
        $endele = '</'.$ignoreele.'>';
        $endpos = $this->getStrPos($content, $endele);
        if($endpos >=0){
            $content = substr($content, $endpos + strlen($endele));
        }else{
            $content = '';
        }
        return $content;

    }

    private function dealNoNewlineBlock($content, $ele){
        $endele = '</'.$ele.'>';
        $endpos = $this->getStrPos($content, $endele);
        $startpos = $this->getStrPos($content, '>') + 1;
        $ahead = '';
        if($endpos >=0){ 
            $ahead = substr($content, $startpos, $endpos-$startpos);
            $content = substr($content, $endpos + strlen($endele));
        }else{
            $ahead = substr($content, $startpos);
            $content = '';
        }
        $attresult = array();
        if(strlen($ahead) > 0){
            $attresult = $this->evalContentBlocks($ahead);
        }
        return array('next'=>$content, 'text'=>$attresult);
       
    }

    private function evalEleName($cont){
        $cont = substr($cont, 1);
        $posspace = $this->getStrPos($cont, ' ');
        $posend = $this->getStrPos($cont, '>');
        if($posspace>=0){
            return substr($cont, 0, $posspace);
        }else{
            return substr($cont, 0, $posend);
        }
    }

    private function pushContentToArray($arr, $cont, $nonewline){
        if($cont=='表格数据暂时无法显示')
            $cont = ' ';
        if($nonewline){
            if(sizeof($arr)==0){
                array_push($arr, $cont);
            }else{
                $sz = sizeof($arr);
                $arr[$sz-1] = $arr[$sz-1] . $cont;
            }
        }else{
            if(mb_strlen($cont)==1&&Utils::isSpecialChar($cont)){}
            else
                array_push($arr, $cont);
        }
        return $arr;
    }

    private function evalContentBlocks($content){
        $contentblock = array();
        $nonewline = false;
        while(true){
            $pos = $this->getStrPos($content, '<');
            if($pos < 0){
                if(strlen(trim($content))>0){
                    $contentblock = $this->pushContentToArray($contentblock, $content, $nonewline);
                    $nonewline  = true;
                }
                break;
            }else if($pos > 0 ){
                $head = substr($content, 0, $pos);
                if(strlen(trim($head))>0){
                    $contentblock = $this->pushContentToArray($contentblock, $head, $nonewline);
                    $nonewline  = true;
                }
                $content = substr($content, $pos);
            }
            $endpos = $this->getStrPos($content, '>');
            if($endpos < 0){
                break;
            }else{
                $ele = substr($content, 0, $endpos + 1);

                if($endpos + 1==strlen($content)){
                    break;
                }
                if($this->getStrPos($ele, '</')==0){
                    $content = substr($content, $endpos + 1);
                }else if($this->getStrPos($ele, '/>')>0){
                    $content = substr($content, $endpos + 1);
                }else{
                    $elename = $this->evalEleName($ele);
                    
                    if(array_key_exists($elename, $this->ignoreBlocks)){
                        $nonewline = false;
                         $content = $this->dealIgnoreBlock($content, $elename);
                    }else if(array_key_exists($elename, $this->nonewlineBlocks)){
                        $re = $this->dealNoNewlineBlock($content, $elename);
                        $content = $re['next'];
                        $msg = $re['text'];

                        if(sizeof($msg)>0){
                            $str = '';
                            foreach ($msg as $m) {
                                $str.=$m;
                            }
                           $contentblock = $this->pushContentToArray($contentblock, $m, $nonewline);
                        }
                        $nonewline = true;
                    }else{
                        $nonewline = false;
                         $content = substr($content, $endpos + 1);
                    }
                }
            }

        }

        return $contentblock;
    }

    private function handleContents($contents){
        $images = array();
        $contentblock = array();
        foreach($contents as $content){
            $imgs = $this->findAllElementIn('img', $content, '', true);
            foreach($imgs as $img){
                $imgsrc = $this->getSrcFromImageEle($img);
                if($this->getStrPos($imgsrc, 'list-dot.gif')<0
                    &&$this->getStrPos($imgsrc, 'ic_opts.png')<0)
                    array_push($images, $imgsrc);
            }
            $blocks = $this->evalContentBlocks($content);
            foreach ($blocks as $block) {
                array_push($contentblock, $block);
            }
        }
        $this->saveMessageTxt($contentblock, $images);

        return array('contentblock'=>$contentblock, 
            'images'=>$images, 'md5v'=>$this->md5v, 'record'=>$this->loadContentFromFile(self::$SELECTED_FILE));
    }
    /*
    private function parseHTMLContentSubprocess2($url){
           $content = $this->getHTMLContentFromURL($url);
           $html = new SimpleHTMLDOM();
           $html->load($content);
           $node = $this->cutBaiduBaikeContent($html);
           $imgs = $node->find('img');
           $urls = array();
           foreach($imgs as $img){
               $imgsrc = $this->getSrcFromImageEle($img->outertext);
               if(!strpos($imgsrc, 'list-dot.gif')){
                    array_push($urls, $imgsrc);
               }
           }
           $leftfull = '余下全文';
           $restlink = '';
           if(strpos($node->innertext, $leftfull)){
                $as = $node->find('a');
                for($i=sizeof($as)-1; $i>=0; $i--){
                    if($as[$i]->text()==$leftfull){
                        $restlink = $this->getHrefFromA($as[$i]->outertext);
                        break;
                    }
                }
           }

           return array('images'=>$urls, 'sublink'=>$restlink) ;       
    }*/


    private function parseBaiduBaikeHTMLContent($url, $prevfile=''){
        $result = $this->parseHTMLContentSubprocess($url);
        if(strlen($result['next'])==0){
            $contentarr = array();
            if(strlen($prevfile)>0){
                if($this->getStrPos($prevfile, ';')>=0){
                    $files = explode(';', $prevfile);
                    foreach ($files as $onefile) {
                        array_push($contentarr, $this->loadContentFromFile($onefile));
                    }
                }else{
                    array_push($contentarr, $this->loadContentFromFile($prevfile));
                }
            }
            array_push($contentarr, $result['content']);
            return $this->handleContents($contentarr);
        }else{
            $file = $this->putContentToFile($result['content']);
            if(strlen($prevfile)>0){
                $file = $prevfile.';'.$file;
            }
            return array('next'=>$result['next'], 'prev'=>$file, 'md5v'=>$this->md5v);
        }

        // if(strpos($url, ';')){
        //     $urls = explode(';', $url);
        //     foreach($urls as $u){
        //          $this->parseHTMLContentSubprocess($u);
        //     }
        // }else{
        //     $this->parseHTMLContentSubprocess($url);
        // }
    }

    private function refreshAll($url){
        //$this->removePath();
        $result = $this->parseBaiduBaikeHTMLContent($url);
        if(array_key_exists('next', $result)){
           return $result;
        }
        return $this->loadFromLocal($result);
    }

    private function loadFromLocal($result=array()){
        if(sizeof($result)==0){
             $result = $this->loadMessageTxt();
        }
       
        return $result;
    }

    private function evalHost($url){
        $prostr = '://';
        $propos = strpos($url, $prostr);
        $resturl = $url;
        $ahead = '';
        if($propos > 0){
            $resturl = substr($url, $propos + strlen($prostr));
            $ahead = substr($url, 0, $propos + strlen($prostr));
        }
        $cutpos = strpos($resturl, '/');
        $resturl = substr($resturl, 0, $cutpos );
        $this->host = $ahead . $resturl;
    }

    private function attachBasicAttrs($functype, $arr=array()){
        $attrs = MD\SysAttr::where('depend', 'ip')->orWhere('depend', $functype)->orderBy('sort')->get();
        $newarr = array();
        foreach($attrs as $attr){
            $one = array('name'=>$attr->name, 'code'=>$attr->code);
            array_push($newarr, $one);
        }

        $arr['sysAttr']=$newarr;
        return $arr;
    }

    public function fetchFromBaiduBaike2(){
        $url = Input::get('url');
        $previousFile = Input::get('prev');
        $this->md5v = Input::get('md5v');
        $functype = Input::get('functype');

        $result =  $this->parseBaiduBaikeHTMLContent($url, $previousFile);
        return view('common.baiduedit', $this->attachBasicAttrs($functype, $this->attachPrevValues(array('result'=>$result))));

    }

    //options: 0=>存在则什么都不作 1=>存在则从本地文件加载 2=>存在则重来
    public function fetchFromBaiduBaike(){
        $url = Input::get('url');
        $option = Input::get('option');
        $functype = Input::get('functype');

        if(strpos($url, 'wapbaike.baidu.com')<0){
            return 'false';
        }

        $this->md5v = Utils::getMD5Value($url);

        if($this->isExists($this->md5v)){
            switch ($option) {
                case '0':
                    $result = $this->loadFromLocal();
                    break;
                case '1':
                    $result = $this->loadFromLocal();
                    break;
                case '2':
                    $result = $this->refreshAll($url);
                    break;
                default:
                    $result =  $this->loadFromLocal();
                    break;
            }
        }else{
            $result = $this->refreshAll($url);
        }
        if(array_key_exists('next', $result)){
            return view('common.baiduwait', $this->attachPrevValues($result));
        }else{
            return view('common.baiduedit', $this->attachBasicAttrs($functype, $this->attachPrevValues(array('result'=>$result))));
        }
        
    }

    private function attachPrevValues($arr=array(), $needtype=true){
        $keys = array('zpm', 'fmt', 'zz', 'jbxx',
                    'nrms', 'zj', 'zjt', 'zjms', 'functype');

        foreach ($keys as $key ) {
            $arr[$key] = Input::get($key);
        }
        if($needtype){
             $arr['type'] = Input::get('type');
        }
       
        return $arr;
    }

    public function displayEditor2(){
        $rec = Input::get('record');

        $this->md5v = Input::get('md5v');
        $this->putContentToFile($rec, self::$SELECTED_FILE);


        return view('common.baiduedit2', 
                $this->attachPrevValues());
    }

    public function applySearch2(){
        $url = 'http://wapbaike.baidu.com/search?word='.Input::get('search');
        $content = $this->getHTMLContentFromURL($url, true);
        $pos1 = strpos($content, 'URL=');
        $content = substr($content, $pos1 + 4);
        $href = 'http://wapbaike.baidu.com'.substr($content, 0, strpos($content, '\''));
        if($this->getStrPos($href, 'http://wapbaike.baidu.com/searchresult')>=0){
            return $this->applySearch2($href);
        }else{
            return '[{"href":"'.$href.'", "content":"'.Input::get('search').'"}]';
        }
        
    }
    public function applySearch(){
        $url = 'http://wapbaike.baidu.com/searchresult/?word='.Input::get('search');
        $content = $this->getHTMLContentFromURL($url, false);
        $mainobjf = '<div class="main"';
        $validcontent = $this->findAllElementIn('div', $content, $mainobjf);
        $content = $validcontent[0]; 
        $mainobjf = '<div class="item"';
        $items =  $this->findAllElementIn('div', $content, $mainobjf);
        $returnresult = '[';
        foreach($items as $item){
            if($this->getStrPos($item, 'class="item"')>0){
                $href = $this->getHrefFromA($item);
                if(strpos($href, '/')==0){
                    $href = $this->host.$href;
                }
                $cs = $this->evalContentBlocks($item);
                $ct = 0;
                if(sizeof($cs)>0){
                    $returnresult.='{"href":"'.$href.'", "content":"'.Utils::cutString($cs[0], 40)['content'].'"},';
                }
            }
        }     
        if(strlen($returnresult)>1)
            $returnresult = substr($returnresult, 0, strlen($returnresult)-1);
        $returnresult.=']';
        return $returnresult;
    }

 
}
