<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace strtob\yii2helpers;

use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;

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

    /**
     * Returns a nested array based on a model's parent-child relationships.
     *
     * @param string $modelClass The ActiveRecord model class name.
     * @param string $idField The name of the ID field (default 'id').
     * @param string $nameField The name of the name field (default 'name').
     * @param string $parentField The name of the parent ID field (default 'parent_id').
     * @param array $conditions Additional conditions for filtering (optional).
     * @return array
     */
    public static function getNestedArray(
        string $modelClass,
        string $idField = 'id',
        string $nameField = 'name',
        string $parentField = 'parent_id',
        array $conditions = []
    ): array {
        // Validate that the model class is an ActiveRecord
        if (!is_subclass_of($modelClass, ActiveRecord::class)) {
            throw new \InvalidArgumentException("The class $modelClass must be an instance of ActiveRecord.");
        }

        // Step 1: Retrieve all records from the model, applying any additional conditions
        $data = $modelClass::find()->where($conditions)->asArray()->all();

        // Step 2: Create a lookup array by ID
        $dataById = \yii\helpers\ArrayHelper::index($data, $idField);

        // Step 3: Build the nested structure
        $nestedArray = [];
        foreach ($data as &$item) {
            if ($item[$parentField] === null) {
                // Top-level item (no parent)
                $nestedArray[] = &$dataById[$item[$idField]];
            } else {
                // Nested item (has a parent)
                if (!isset($dataById[$item[$parentField]]['children'])) {
                    $dataById[$item[$parentField]]['children'] = [];
                }
                $dataById[$item[$parentField]]['children'][] = &$dataById[$item[$idField]];
            }
        }

        // Return only the top-level items, now with nested children
        return array_values($nestedArray);
    }
}


