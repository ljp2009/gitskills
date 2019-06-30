<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Image;
class ResourceController extends Controller
{
    public function getDefImage()
    {
        $uploadParams =Image::getUploadAliImageParams();
        return view('/admins.pages.defimage',['uploadParams'=>$uploadParams]);
    }
}
