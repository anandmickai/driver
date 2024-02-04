<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Errors Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'E_NO_ERRORS' => [
        'httpCode' => 200,
        'message' => ''
    ],
    'E_VALIDATION' => [
        'httpCode' => 422,
        'message' => 'Unprocessable Entity'
    ],
    'E_UNAUTHORIZED' => [
        'httpCode' => 401,
        'message' => 'Unauthorized access'
    ],
    'E_UNAUTHENTICATED' => [
        'httpCode' => 401,
        'message' => 'Unauthenticated access'
    ],
    'E_NOT_ACCEPTABLE' => [
        'httpCode' => 406,
        'message' => 'Resource information not acceptable'
    ],
    'E_SYSTEM' => [
        'httpCode' => 500,
        'message' => 'Internal server error occurred'
    ],
    'E_CLIENT_IP' => [
        'httpCode' => 400,
        'message' => 'Client Ip Address not valid'
    ],
    'E_REQUEST_INVALID' => [
        'httpCode' => 400,
        'message' => 'Bad request'
    ],
    'E_CONFLICT' => [
        'httpCode' => 409,
        'message' => 'The request could not be completed due to a conflict'
    ],
    'E_THROTTLE' => [
        'httpCode' => 429,
        'message' => 'Too Many Requests'
    ],
    'E_NOT_FOUND' => [
        'httpCode' => 404,
        'message' => 'Resource not found'
    ],
    'E_METHOD_NOT_ALLOWED' => [
        'httpCode' => 406,
        'message' => 'Resource information not acceptable'
    ],
    'E_PRECONDITION' => [
        'httpCode' => 406,
        'message' => 'preconditions in its headers failed'
    ],
    'E_INVALID_OTP' => [
        'httpCode' => 422,
        'message' => 'Invalid OTP'
    ],
    'E_EXCEED_MAX_ATTEMPTS_OTP' => [
        'httpCode' => 429,
        'message' => 'Exceeded maximum OTP validation tries'
    ],
    'E_OTP_EXPIRED' => [
        'httpCode' => 408,
        'message' => 'OTP expired.'
    ],
    'E_EXCEED_RESEND_LIMIT_OTP' => [
        'httpCode' => 422,
        'message' => 'Exceeded maximum resend OTP limit'
    ],
    'E_AUTH_FROZEN' => [
        'httpCode' => 401,
        'message' => 'Your account is frozen'
    ],
    'E_OTP_SENT' => [
        'httpCode' => 200,
        'message' => 'OTP sent to registered mobile number'
    ],
    'E_UNSUPPORTED_DEVICE' => [
        'httpCode' => 406,
        'message' => 'Unsupported Device'
    ],
    'E_UNKNOWN' => [
        'httpCode' => 422,
        'message' => 'Unknown error occurred'
    ],
    'E_UNPROCESSABLE' => [
        'httpCode' => 422,
        'message' => 'Unprocessable Entity'
    ],
    'E_NO_CONTENT' => [
        'httpCode' => 422,
        'message' => 'No Content'
    ],

];
