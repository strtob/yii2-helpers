<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace strtob\yii2helpers;

use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

/**
 * Description of ArHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class ArrayHelper
{

    public static function multiArrayToString($arrs, $sep = ' / ')
    {
        array_walk_recursive($arrs, function ($v) use (&$result) {
            $result[] = $v;
        });
        return implode('$sep', $result);
    }

}
