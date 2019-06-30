<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Common\RedirectCode;
use App\Common\RedirectCodeNew;

class GotoController extends Controller
{
    public function index($code)
    {
        $rc = new RedirectCodeNew($code);
        return redirect($rc->getUrl());
    }
    public function generate(){

    }

}
