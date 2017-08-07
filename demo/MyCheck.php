<?php
/**
 * Created by PhpStorm.
 * User: hys
 * Date: 17/8/7
 * Time: 上午10:17
 */
require('../Check.php');
class MyCheck extends Check
{
    public function myok($values)
    {
        if(1)
        {
            return '{$name} nihao';
        }
        else
        {
            return '';
        }
    }
}