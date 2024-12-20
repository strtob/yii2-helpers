<?php

namespace strtob\yii2helpers;

/**
 * Returns Awesome Icon html tag (Template <i class="{class}"></i>) < based on keyword by use of get($val, $addCssClass = 'me-1', $withHtmlTag = false).
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class IconHelper
{

    public static $template = '<i class="{class}"></i>';

    private static $lookup = [
        // ___A___
        'abortreason' => 'fa-solid fa-power-off',
        'asset' => 'fa-regular fa-building',
        'articleofassociation' => 'fa-solid fa-list-ol',
        'address' => 'fa-solid fa-map-location-dot',
        'assign' => 'fa-solid fa-arrow-right-arrow-left',
        'assignment' => 'fa-solid fa-arrow-right-arrow-left',
        'agreement' => 'fa-solid fa-file-signature',
        'approval' => 'fa-solid fa-file-signature',
        'attendee' => 'fa-solid fa-users-line',
        'analyze' => 'fa-solid fa-chart-column',

        // 
        // ___B___
        'borrower' => 'fa-solid fa-user',
        'basic' => 'fa-solid fa-home',
        'party' => 'fa-solid fa-user-group',
        'bankaccounttransaction' => 'fa-solid fa-list',
        'bank' => 'fa-solid fa-university',

        // 
        // ___C___
        'content' => 'fa-regular fa-file-lines',
        'company' => 'fa-solid fa-industry',
        'contract' => 'fa-solid fa-file-contract',
        'comment' => 'fa-regular fa-comments',
        'comission' => 'fa-solid fa-money-bill',
        'costcenter' => 'fa-solid fa-industry',
        'costrate' => 'fa-solid fa-sack-dollar',

        // 
        // ___D___
        'download' => 'fa-solid fa-download',
        'datatype' => 'fa-regular fa-rectangle-list',
        'duration' => 'fa-solid fa-clock-rotate-left',
        'dashboard' => 'fa-solid fa-dashboard',
        'daterange' => 'fa-solid fa-clock-rotate-left',
        'document' => 'fa-regular fa-file-lines',
        'department' => 'fa-solid fa-users',
        'database' => 'fa-solid fa-database',
        'department' => 'fas fa-users',

        // 
        // ___E___
        'essentials' => 'fa-solid fa-bars',
        'external' => 'fa-solid fa-arrow-up-right-from-square',
        'entitycapitalmeasurement' => 'fa-solid fa-wallet',
        'entityboardresoution' => 'fas fa-book',
        'email' => 'fa-regular fa-envelope',
        'event' => 'fa-solid fa-calendar-day',
        'escalationclause' => 'fa-solid fa-chart-line',
        'entity' => 'fa-solid fa-city',
        'externaluser' => 'fa-solid fa-user-shield',
        'export' => 'fa-solid fa-file-export',
        'excelexport' => 'fa-regular fa-file-excel',

        // 
        // ___F___
        'further' => 'fa-solid fa-sliders',
        'file' => 'fa-solid fa-file',

        // 
        // ___G___
        'group' => 'fa-solid fa-user-group',

        //        
        // ___I___
        'issuer' => 'fa-solid fa-user-tie',

        //        
        // ___K___
        'knowledgebase' => 'fa-solid fa-book-open',

        //        
        // ___L___
        'loan' => 'fa-solid fa-building-columns',
        'value' => 'fa-solid fa-chart-simple',
        'list' => 'fa-solid fa-list',
        'linktable' => 'fa-solid fa-link',
        'link' => 'fa-solid fa-link',
        'log' => 'fa-solid fa-table-list',

        //        
        // ___M___
        'meta' => 'fa-solid fa-user',
        'management' => 'fa-solid fa-user-tie',
        'mandate' => 'fa-solid fa-users',
        'module' => 'fa-solid fa-puzzle-piece',
        'meeting' => 'fa-solid fa-calendar-day',
        //
        // ___N___
        'payment' => 'fa-regular fa-credit-card',
        'person' => 'fa-solid fa-person',
        'profitcenter' => 'fas fa-cash-register',

        // ___N___
        'query' => 'fa-solid fa-database',
        'question' => 'fa-solid fa-question',

        //
        // __R__
        'research' => 'fa-solid fa-chart-column',
        'related' => 'fa-solid fa-arrows-rotate',
        'renewal' => 'fa-solid fa-repeat',
        'related' => 'fa-solid fa-arrows-rotate',
        'relations' => 'fa-solid fa-repeat',
        'relation' => 'fa-solid fa-repeat',
        'relationship' => 'fa-solid fa-repeat',
        'role' => 'fa-solid fa-masks-theater',
        'recurrencepattern' => 'fa-solid fa-rotate-right',
        'report' => 'fa-solid fa-chart-bar',
        'role' => 'fa-solid fa-masks-theater',

        // 
        // ___S___
        'status' => 'fa-solid fa-list-check',
        'setting' => 'fa-solid fa-cog',
        'server' => 'fa-solid fa-server',
        'search' => 'fa-solid fa-search',
        'system' => 'fa-solid fa-cogs',
        'sys' => 'fa-solid fa-cogs',
        'sysfile' => 'fa-solid fa-file',
        'sysfilehaslink' => 'fa-solid fa-link',


        // 
        // ___T___
        'type' => 'fa-solid fa-gear',
        'task' => 'fa-solid fa-list-check',
        'timeline' => 'fa-solid fa-timeline',
        'tax' => 'fa-solid fa-building-columns',
        'tracking' => 'fas fa-pencil-alt',
        'transaction' => 'fa-solid fa-money-bill',
        'template' => 'fa-solid fa-file',
        'tag' => 'fa-solid fa-tag',
        'table' => 'fa-solid fa-table',
        'time' => 'fa-solid fa-clock',


        // 
        // ___I___
        'investmentoffer' => 'fas fa-mail-bulk',
        'number' => 'fa-solid fa-phone',
        'import' => 'fa-solid fa-file-import',
        'importexcel' => 'fa-regular fa-file-excel',
        'importcsv' => 'fa-regular fa-file-csv',
        'importpdf' => 'fa-regular fa-file-pdf',
        'importword' => 'fa-regular fa-file-word',
        'importimage' => 'fa-regular fa-file-image',
        'importzip' => 'fa-regular fa-file-archive',
        'importaudio' => 'fa-regular fa-file-audio',
        'importvideo' => 'fa-regular fa-file-video',
        'importtext' => 'fa-regular fa-file-alt',
        'importxml' => 'fa-regular fa-file-code',
        'importjson' => 'fa-regular fa-file-code',
        'importhtml' => 'fa-regular fa-file-code',
        'importphp' => 'fa-regular fa-file-code',
        'importcss' => 'fa-regular fa-file-code',
        'importjs' => 'fa-regular fa-file-code',
        'importsql' => 'fa-regular fa-file-code',
        'importjava' => 'fa-regular fa-file-code',
        'importc' => 'fa-regular fa-file-code',
        'importcpp' => 'fa-regular fa-file-code',
        'importcsharp' => 'fa-regular fa-file-code',
        'importpython' => 'fa-regular fa-file-code',

        // 
        //
        // ___W___       
        'website' => 'fa-regular fa-window-maximize',
        'workingtime' => 'fas fa-clock',
        'workingtimetable' => 'fas fa-clock',
        'workingtimetableentry' => 'fas fa-clock',
        

        //
        //
        // ___U___
        'user' => 'fas fa-user',
        'usergroup' => 'fas fa-users',
        'userrole' => 'fas fa-user-tag',
        'userroleassignment' => 'fas fa-user-tag',
        
        
    ];
    private static $default = 'fa-solid fa-book';

    public static function get($val, $addCssClass = 'me-1', $withHtmlTag = false)
    {
        $val = strtolower($val);

        // Check if an exact key match exists
        if (array_key_exists($val, self::$lookup)) {
            $r = '';

            if ($withHtmlTag)
                $r = str_replace('{class}', (self::$lookup[$val] . ' ' . $addCssClass), self::$template);
            else
                $r = self::$lookup[$val] . ' ' . $addCssClass;

            return $r;
        } else
            if ($withHtmlTag)
                return str_replace('{class}', (self::$default . ' ' . $addCssClass), self::$template);
            else
                return self::$default . ' ' . $addCssClass;
    }
}
