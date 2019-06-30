<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommonWechatController extends Controller
{
        public function downloadMediasFromWechat($serve_ids, $accessToken, $mediaType){
            $serveIds = explode(",", $serve_ids);
            $jsonstr = '[';
            $ct = 0;
            foreach ($serveIds as $id) {
                $ct ++;
                 $medialoc = $this->downloadMediaFromWechat($id, $accessToken, $mediaType);
                 $jsonstr.='"'.$medialoc.'"';
                if($ct < sizeof($serveIds)){
                    $jsonstr.=',';
                }
           }
            $jsonstr .=']';
            return $jsonstr;
        }

        public function downloadMediaFromWechat($serve_id, $accessToken, $mediaType){
            $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token="
                        .$accessToken."&media_id=".$serve_id;
            $content = $this->httpGet($url);
            $locate = "1.jpg"; //TODO where to put media
            $fh = fopen($locate, 'w') ;  
            fwrite($fh, $content) ;  
            fclose($fh) ; 

            return $locate;
        }

        private function getJsApiTicket($appId, $appSecret) {
             $data = json_decode(file_get_contents("jsapi_ticket.json"));
            if ($data->expire_time < time()) {
              $accessToken = $this->getAccessToken($appId, $appSecret);
              // 如果是企业号用以下 URL 获取 ticket
              // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
              $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
              $res = json_decode($this->httpGet($url));
              $ticket = $res->ticket;
              if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
              }
            } else {
              $ticket = $data->jsapi_ticket;
            }

            return $ticket;
        }

        public function getAccessToken($appId, $appSecret) {
            $data = json_decode(file_get_contents("access_token.json"));//Can be put to memcache in future
            if ($data->expire_time < time()) {
              $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
              $res = json_decode($this->httpGet($url));
              $access_token = $res->access_token;
              if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
              }
            } else {
              $access_token = $data->access_token;
            }
            return $access_token;
        }

        private function httpGet($url) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 500);
            // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
            // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($curl, CURLOPT_URL, $url);

            $res = curl_exec($curl);
            curl_close($curl);

            return $res;
          }
 
}
