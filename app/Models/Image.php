<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 't_image';
    protected $guarded = ['id'];

    public static function getImagesPath($objType, $imgType, $id)
    {
    	$img = Image::where('type','=', $imgType)
    		->where('obj_type','=',$objType)
    		->where('obj_id','=',$id)->get()->first();
    	if($img)
    	{
    		///pic/cover_3body_md
    		$nm = explode('.', $img->name)[0];
    		return '/pic/'.$imgType.'_'.$nm.'_md';
    	}
    	else
		{
			return 'default.jpg';
		}

    }	
}

