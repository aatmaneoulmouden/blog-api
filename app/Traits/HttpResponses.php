<?php

namespace App\Traits;

trait HttpResponses
{
    /**
     * HTTP Response for success
     *
     * @param string $dataLabel
     * @param any $dataValue
     * @param integer $code
     * @return void
     */
    protected function success($dataLabel, $dataValue, $code = 200)
    {
        return response()->json(
            [
                'status' => 'successgi',
                $dataLabel => $dataValue,
            ],
            $code
        );
    }

    /**
     * HTTP Response for error
     *
     * @param string $message
     * @param integer $code
     * @return void
     */
    protected function error($message, $code)
    {
        return response()->json(
            [
                'status' => 'error',
                'message' => $message,
            ],
            $code
        );
    }
}
