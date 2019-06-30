<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    const PAGEMAP = [
        'ip'               => '/ip/{id}',
        'ipscene'          => '/ipscene/{id}',
        'iprole'           => '/roles/{id}',
        'ipdialogue'       => '/ipdialogue/{id}',
        'userproduction'   => '/user/product/{id}',
        'dimension'        => '/dimpub/list/diminfo/0/{id}',
        'dimensionpublish' => '/dimpub/{id}',
        'act'              => '/act/getshowjoin/{id}',
        'back'             => '',
        '0'                => '/reshall',
    ];
    const BACKSTEP = 2;
    public function getLoading(Request $request, $params='0_0'){
        $arr = explode('_', $params);
        $url = self::PAGEMAP[$arr[0]];
        $url = str_replace('{id}', $arr[1], $url);
        $settings = [
            'flag'     => true,
            'url'      => $url,
            'backStep' => self::BACKSTEP
        ];
        if($arr[0] == 'back'){
            $settings['flag'] = false;
            $settings['backStep'] = $arr[1];
        }
        $sen = $request->session()->put('noback', $settings);
        return view('loading');
    }
    public function postLoading(Request $request){
        $settings = $request->session()->get('noback');
        if(!is_null($settings)){
            if($settings['flag']){
                $settings['flag'] = false;
                $request->session()->put('noback', $settings);
                return response()->json([
                    'res'  => true,
                    'info' => $settings['backStep'],
                    'url'  => $settings['url'],
                    'debug'=>'1'
                ]);
            }else{
                return response()->json([
                    'res'  => false,
                    'info' => $settings['backStep'],
                    'url'  => $settings['url'],
                    'debug'=>'2'
                ]);

            }
        }else{
            return response()->json([
                'res' =>false,
                'info' =>2,
                'url' =>'/reshall',
                'debug'=>'3'
            ]);
        }
    }
}
