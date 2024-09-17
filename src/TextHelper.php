<?php

namespace strtob\yii2helpers;

/**
 * 
 * Description of TextHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
* 
 *  */
class TextHelper
{

    /**
     * 
     * @param type $text Text to calculate average
     * @param type $wpm = 200
     * @return type array Keys: minutes and seconds
     */
    public static function estimateReadingTime($text, $wpm = 200)
    {
        $totalWords = str_word_count(strip_tags($text));
        $minutes    = floor($totalWords / $wpm);
        $seconds    = floor($totalWords % $wpm / ($wpm / 60));

        return array(
            'minutes' => $minutes,
            'seconds' => $seconds
        );
    }

}
