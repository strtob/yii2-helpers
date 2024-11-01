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
     * Returns a nested array structure with customized `id`, `label`, and `items` fields.
     *
     * @param string $modelClass The ActiveRecord model class name.
     * @param string $idField The name of the ID field.
     * @param string $labelField The name of the label field (for display).
     * @param string $parentField The name of the parent ID field.
     * @param string $iconClass The icon class to be used in the label.
     * @param array $conditions Additional conditions for filtering (optional).
     * @return array
     */
    public static function getNestedArray(
        string $modelClass,
        string $idField = 'id',
        string $labelField = 'name',
        string $parentField = 'parent_id',
        string $iconClass = 'fa fa-star',
        array $conditions = []
    ): array {
        // Ensure the model class is a valid ActiveRecord class
        if (!is_subclass_of($modelClass, ActiveRecord::class)) {
            throw new \InvalidArgumentException("The class $modelClass must be an instance of ActiveRecord.");
        }

        // Fetch data with conditions and limit fields to id, label, and parent
        $data = $modelClass::find()->select([$idField, $labelField, $parentField])->where($conditions)->asArray()->all();

        // Index data by ID for easy referencing
        $dataById = [];
        foreach ($data as $item) {
            $dataById[$item[$idField]] = [
                'id' => $item[$idField],
                'label' => "<i class=\"$iconClass\"></i> " . $item[$labelField],
                'parent_id' => $item[$parentField],
                'items' => []  // Initialize children
            ];
        }

        // Step 3: Build the nested structure
        $nestedArray = [];
        foreach ($dataById as &$item) {
            $parentId = $item['parent_id'];
            if ($parentId === null) {
                // Top-level item (no parent)
                $nestedArray[] = &$item;
            } else {
                // Nested item (has a parent)
                if (isset($dataById[$parentId])) {
                    $dataById[$parentId]['items'][] = &$item;
                }
            }
        }

        // Step 4: Remove `parent_id` from each item in the nested structure
        self::removeParentId($nestedArray);

        return $nestedArray;
    }

    /**
     * Recursively removes the `parent_id` field from each item in the array.
     *
     * @param array &$array The array to process.
     */
    private static function removeParentId(array &$array)
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                unset($item['parent_id']);
                if (isset($item['items']) && is_array($item['items'])) {
                    self::removeParentId($item['items']);
                }
            }
        }
    }
}


