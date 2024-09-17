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
class ArHelper
{

    /**
     * Return all ActiveRecords Models, e.g. for Select2
     * @param type $withId
     * @param type $modelNamespace
     * @param type $modelsPath
     * @return string
     */
    public static function listAllArModels($withId = false, $modelNamespace = 'backend\models', $modelsPath = null)
    {

        if ($modelsPath === null)
            $modelsPath = \Yii::getAlias('@backend/models');

        $classNames = [];

        $subDirs   = FileHelper::findDirectories(realpath($modelsPath), ['recursive' => true]);
        $subDirs[] = $modelsPath; // Include the models directory itself

        foreach ($subDirs as $subDir)
        {
            $files = FileHelper::findFiles(realpath($subDir), [
                        'only'          => ['*.php'],
                        'caseSensitive' => true
            ]);

            foreach ($files as $file)
            {
                $className = $modelNamespace . str_replace([$modelsPath, '/', '.php'], ['', '\\', ''], $file);

                // Add the fully qualified class name to the $classNames array
                if ($withId)
                    $classNames   = array_merge($classNames, [$className => $className]);
                else
                    $classNames[] = $className;
            }
        }


        return $classNames;
    }

    public static function arrayToString($arrs, $sep = ' ')
    {
        if (!is_array($arrs))
            return $arrs;

        $list = array();
        foreach ($arrs as $arr)
        {
            if (is_array($arr))
            {
                $list[] = self::arrayToString($arr, $sep);
            } else
            {
                $list[] = JString::trim($arr);
            }
        }
        return implode($sep, $list);
    }

    /**
     * Retrieve attributes based on the model class.
     *
     * @param string $modelClass The fully qualified model class name.
     * @return array The array of attributes.
     */
    public static function getAttributesByModelClass($modelClass)
    {
        // Implement your logic here to retrieve attributes based on the model class
        // Return an array of attributes
        // Example implementation:
        $model      = new $modelClass();
        $attributes = $model->attributes();

        // If you want to exclude certain attributes, you can use ArrayHelper::remove() method
        // For example, to remove 'created_at' and 'updated_at' attributes:
        // ArrayHelper::remove($attributes, ['created_at', 'updated_at']);

        return $attributes;
    }

    /**
     * Prepare Models to array and has parent_id relation to optgroup for select2
     * @param array $data
     * @return array
     */
    public static function prepareModel4OptGroup(array $data): array
    {
        $result = [];

        foreach ($data as $item)
        {
            if (!is_null($item->parent))
            {
                $parentName          = $item->parent->name;
                if (!isset($result[$parentName]))
                    $result[$parentName] = [];

                $result[$parentName][$item->id] = $item->name;
            }
        }

        return $result;
    }

    /**
    * Recursively collects all `db_lock` values from the current model and its nested related models.
    *
    * @param \yii\db\ActiveRecord $model The model instance to analyze.
    * @param string $namespace The base namespace to remove from class names.
    * @return array An array containing `db_lock` values with their class names.
    */
    public static function collectDbLocks($model, $namespace = '\\app\\models\\')
    {
        $dbLocks = [];

        // Helper function to recursively collect `db_lock` values
        $collect = function($model, &$dbLocks) use (&$collect, $namespace) {
            if ($model instanceof \yii\db\ActiveRecord) {
                // Collect `db_lock` from the current model
                if (isset($model->db_lock)) {
                    // Get the class name and remove the namespace
                    $className = get_class($model);
                    // Strip the namespace if it matches
                    if (strpos($className, $namespace) === 0) {
                        $className = substr($className, strlen($namespace));
                    }
                    // Extract just the class name (last segment) and add to dbLocks array
                    $className = substr($className, strrpos($className, '\\') + 1);
                    // Add to dbLocks array
                    $dbLocks[$className] = $model->db_lock;
                }

                // Get related models and iterate over them
                foreach ($model->relationNames() as $relationName => $relation) {
                    if (is_array($relation)) {
                        // If it's a `hasMany` relationship
                        foreach ($relation as $relatedModel) {
                            $collect($relatedModel, $dbLocks);
                        }
                    } elseif ($relation instanceof \yii\db\ActiveRecord) {
                        // If it's a `hasOne` relationship
                        $collect($relation, $dbLocks);
                    }
                }
            }
        };

        // Start recursive collection
        $collect($model, $dbLocks);

        return $dbLocks;
    }




}
