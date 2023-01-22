<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected int $applicationErrorCode;

    public function __construct($message, int $applicationErrorCode) {
        parent::__construct($message, 404);

        $this->applicationErrorCode = $applicationErrorCode;
    }

    public function render()
    {
        return response()->json([
            'applicationErrorCode' => $this->applicationErrorCode,
            'message' => $this->message
        ], 404);
    }
}
