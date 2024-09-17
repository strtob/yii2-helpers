<?php
namespace strtob\yii2helpers;

/**
 * Description of DataConvertHelper
 *
 * @author Tobias Streckel <ts@re-soft.de>
 */
class DataConvertHelper
{

    public static function modelsWithParents2Array($models)
    {
        $types = [];

        foreach ($models as $type)
        {
            if (!is_null($type->parent))
            {
                $parentName         = $type->parent->name;
                if (!isset($types[$parentName]))
                    $types[$parentName] = [];

                $types[$parentName][$type->id] = $type->name;
            }
        }
                  

        return $types;
    }
}
