<?php

namespace strtob\yii2helpers;
/**
 * Description of HtmlResponse
 *
 * @author Tobias Streckel <info@tobias.streckel.de>
 */
class JsonHelper
{

    public static function Json2Table($content, $class = 'table table-bordered table-striped')
    {
        $html = "";
        if ($content != null)
        {
            $arr = json_decode(strip_tags($content), true);

            if ($arr && is_array($arr))
            {
                $html .= self::arrayToHtmlTableRecursive($arr, $class);
            }
        }
        return $html;
    }

    public static function arrayToHtmlTableRecursive($arr, $class = 'table table-bordered table-striped')
    {
        $str = "<table class='$class'><tbody>";
        foreach ($arr as $key => $val)
        {
            $str .= "<tr>";
            $str .= "<td><strong>$key</strong></td>";
            $str .= "<td>";
            if (is_array($val))
            {
                if (!empty($val))
                {
                    $str .= self::arrayToHtmlTableRecursive($val, $class);
                }
            } else
            {
                $val = nl2br($val);
                $str .= "$val";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }

}
