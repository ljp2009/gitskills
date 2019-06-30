<?php
namespace App\Observers;
use App\Models\UserAttr;
class UserObserver
{

    public static function attributeChange($userAttr)
    {
    	echo '调用了一次';
    }
    public static function sumChange($userSum)
    {

    }
    public static function detailChange($userDetail)
    {

    }
}
?>