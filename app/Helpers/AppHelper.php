<?php
namespace App\Helpers;

class AppHelper
{
    public static function instance()
    {
        return new AppHelper();
    }

    public function getRoundStr($intRound)
    {
        // switch ($intRound) {
        //     case 0:
        //         return ""
        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }
        return "increment $intRound";
    }
}