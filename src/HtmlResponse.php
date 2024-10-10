<?php

namespace strtob\yii2helpers;

/**
 * HtmlResponse class
 *
 * Provides static methods for generating standardized HTML responses for
 * success, error, and denial messages, used in Yii2 framework applications.
 *
 * @author Tobias Streckel <info@tobias.streckel.de>
 */
class HtmlResponse
{

    /**
     * Generates a success message response.
     *
     * @param string|null $title The title of the message. Defaults to "Data saved" if null.
     * @param string|null $message The success message. Defaults to a generic "data saved" message if null.
     * @param string|null $redirectUrl A URL to redirect to after the operation.
     * @param array|null $additionData Additional data to include in the response, merged into the response array if provided.
     * @return array The structured success response.
     */
    public static function messageSuccess($title = null, $message = null, $redirectUrl = null, $redirectTarget = null, array $additionData = null)
    {
        // Default title if none provided
        if (is_null($title)) {
            $title = \yii::t('app', 'Data saved');
        }

        // Default message if none provided
        if (is_null($message)) {
            $message = \yii::t('app', 'Your data has been saved successfully.');
        }

        // Base response array
        $response = [
            'data' => [
                'success' => true,
                'title' => \yii::t('app', $title),
                'message' => \yii::t('app', $message),
                'redirectUrl' => $redirectUrl,   
                'redirectTarget' => $redirectTarget,             
            ],
        ];

        // Merge additional data if provided
        if (!is_null($additionData)) {
            $response['data'] = array_merge($response['data'], $additionData);
        }

        return $response;
    }

    /**
     * Generates a denied message response.
     *
     * @param string|null $title The title of the message. Defaults to "Not permitted" if null.
     * @param string|null $message The denied message. Defaults to a generic "not permitted" message if null.
     * @return array The structured denied response.
     */
    public static function messageDenied($title = null, $message = null)
    {
        // Default title if none provided
        if (is_null($title)) {
            $title = \yii::t('app', 'Not permitted');
        }

        // Default message if none provided
        if (is_null($message)) {
            $message = \yii::t('app', 'You are not permitted to perform this action.');
        }

        // Response array for denied message
        return [
            'data' => [
                'success' => false,
                'title' => \yii::t('app', $title),
                'message' => \yii::t('app', $message),
            ],
        ];
    }

    /**
     * Generates an error message response, with optional model data.
     *
     * If the provided model is an instance of ActiveRecord, detailed error information is included.
     * If the model is an instance of ErrorException, its message is returned.
     *
     * @param string|null $title The title of the error message. Defaults to "Error occurred" if null.
     * @param string|null $message The error message. Defaults to a generic "error occurred" message if null.
     * @param \yii\db\ActiveRecord|\yii\base\ErrorException|null $model Optional model or exception for error details.
     * @param string $location The location identifier for logging purposes.
     * @return array The structured error response.
     */
    public static function messageError($title = null, $message = null, $model = null, $location)
    {
        // Default title if none provided
        if (is_null($title)) {
            $title = \yii::t('app', 'Error occurred');
        }

        // Default message if none provided
        if (is_null($message)) {
            $message = \yii::t('app', 'An error occurred. Unable to save record.');
        }

        // Handle case where model is an ActiveRecord instance
        if ($model instanceof \yii\db\ActiveRecord) {
            $errorMessage;
            $errors = (!is_null($model) ? $model->getErrorSummary(true) : '(no model)');

            // Handle error message formatting based on errors
            if (is_string($errors)) {
                $errorMessage = $errors;
            } elseif (empty($errors)) {
                $errorMessage = '(empty)';
            } else {
                $errorMessage = ArrayHelper::multiArrayToString($model->getErrors());
            }

            // Return detailed error response for ActiveRecord models
            return [
                'data' => [
                    'success' => false,
                    'model' => (!is_null($model) ? $model::class : ''),
                    'title' => $title,
                    'message' => $message,
                    'attributes' => (\YII_DEBUG && !is_null($model) ? implode(', ', $model->getAttributes()) : \yii::t('app', 'Please see log.')),
                    'errors' => (\YII_DEBUG ? $errorMessage : \yii::t('app', 'Please see log.')),
                ],
            ];
        }

        // Handle case where model is an ErrorException instance
        elseif ($model instanceof \yii\base\ErrorException) {
            return [
                'data' => [
                    'success' => false,
                    'model' => (!is_null($model) ? $model::class : ''),
                    'title' => $title,
                    'message' => $model->getMessage(),
                ],
            ];
        }

        // Return generic error response if no model provided
        return [
            'data' => [
                'success' => false,
                'title' => $title,
                'message' => $message,
            ],
        ];

        // Log the error message
        \Yii::warning($message, 'app_' . $location);
    }

}
