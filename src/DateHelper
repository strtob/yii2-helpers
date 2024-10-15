<?php

namespace strtob\yii2helpers;

use Yii;

/**
 * Class DateHelper
 *
 * A helper class for date operations, including calculating date differences,
 * checking date ranges, formatting date ranges, and converting PHP date formats
 * to JavaScript date formats.
 *
 * @package strtob\yii2helpers
 * @author Tobias Streckel <ts@re-soft.de>
 */
class DateHelper
{
    /**
     * Calculates the date difference between two dates and returns a formatted message.
     *
     * @param string $start The start date in "Y-m-d" format.
     * @param string $end The end date in "Y-m-d" format.
     * @param string $message The message format with placeholders for years and months. 
     *                        Defaults to '{years} yrs. {months} months'.
     * @return string The formatted message with the calculated date difference.
     */
    public static function calcDateDifference($start, $end, $message = '{years} yrs. {months} months')
    {
        $startDate = new \DateTime($start);
        $endDate   = new \DateTime($end);

        $interval = $startDate->diff($endDate);

        $years   = $interval->y;
        $months  = $interval->m;
        $days    = $interval->d;
        $hours   = $interval->h;
        $minutes = $interval->i;
        $seconds = $interval->s;

        return Yii::t('app', $message, [
            'years'   => $years,
            'months'  => $months,
            'days'    => $days,
            'hours'   => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ]);
    }

    /**
     * Checks if the current date is within the specified range.
     *
     * @param string|null $start The start date of the range in "Y-m-d" format. Defaults to null.
     * @param string|null $end The end date of the range in "Y-m-d" format. Defaults to null.
     * @return bool True if the current date is within the range, false otherwise.
     */
    public static function isDateInRange(?string $start, ?string $end): bool
    {
        $currentDate = date('Y-m-d');

        if ($start === null && $end === null) {
            return true; // Both start and end dates are null, range is considered to be always in range
        }

        if ($start !== null && $currentDate < $start) {
            return false; // Current date is before the start date, not within the range
        }

        if ($end !== null && $currentDate > $end) {
            return false; // Current date is after the end date, not within the range
        }

        return true; // Current date is within the specified range
    }

    /**
     * Checks if a given date is over today.
     *
     * @param string|null $date The date to check (in 'Y-m-d' format).
     * @return bool True if the date is over today, false otherwise.
     */
    public static function isDateOverToday(?string $date): bool
    {
        if ($date === null) {
            return false; // Handle this case based on your specific requirements
        }

        $today     = new \DateTime();
        $givenDate = \DateTime::createFromFormat('Y-m-d', $date);

        return $givenDate !== false && $givenDate < $today;
    }

    /**
     * Format the date range using Yii2 formatter.
     *
     * @param string $startDate The start date in PHP date format.
     * @param string $endDate The end date in PHP date format.
     * @return string The formatted date range.
     */
    public static function formatJsDateRange($startDate, $endDate): string
    {
        $formatter = Yii::$app->formatter;

        // Format start date using the formatter's dateFormat
        $formattedStartDate = $formatter->asDate($startDate, $formatter->dateFormat);

        // Format end date in 'dd.MM.yyyy' format
        $formattedEndDate = $formatter->asDate($endDate, 'dd.MM.yyyy');

        // Combine formatted dates into a date range
        return $formattedStartDate . ' - ' . $formattedEndDate;
    }

    /**
     * Returns the JavaScript date format equivalent of the system's date format.
     *
     * @return string The JavaScript date format.
     */
    public static function systemJsDateFormat(): string
    {
        return self::convertPhpToJsDateFormat(\yii::$app->formatter->dateFormat);
    }

    /**
     * Converts PHP date format to JavaScript date format.
     *
     * @param string $phpFormat The PHP date format string.
     * @return string The JavaScript date format string.
     */
    public static function convertPhpToJsDateFormat($phpFormat): string
    {
        $cleanedFormat = str_replace("php:", "", $phpFormat);

        $formatConversion = [
            // PHP formats => JavaScript equivalents
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => 'N',
            'S' => '',
            'w' => 'w',
            'z' => 'o',
            'W' => 'W',
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            'L' => '',
            'o' => 'YYYY',
            'Y' => 'yyyy',
            'y' => 'yy',
            'a' => 'a',
            'A' => 'A',
            'B' => '',
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'ii',
            's' => 'ss',
            'u' => '',
            'e' => 'zz',
            'I' => '',
            'O' => 'ZZ',
            'P' => 'Z',
            'T' => 'z',
            'Z' => 'X',
            'c' => '',
            'r' => '',
            'U' => 't',
        ];

        return strtr($cleanedFormat, $formatConversion);
    }
}
