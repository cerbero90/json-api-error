<?php

return [
    400 => [
        'title' => 'Bad Request',
        'detail' => 'An error occurred while processing your request, please try again.',
    ],
    401 => [
        'title' => 'Unauthorized',
        'detail' => 'You need to provide the correct login details to access this resource.',
    ],
    402 => [
        'title' => 'Payment Required',
        'detail' => 'The resource you are trying to access requires a payment.',
    ],
    403 => [
        'title' => 'Forbidden',
        'detail' => 'You do not have permission to access this resource.',
    ],
    404 => [
        'title' => 'Not Found',
        'detail' => 'The server could not find the resource you are looking for.',
    ],
    405 => [
        'title' => 'Method Not Allowed',
        'detail' => 'You are trying to access a resource in a way that is not allowed.',
    ],
    406 => [
        'title' => 'Not Acceptable',
        'detail' => 'The server cannot provide the data in the way you asked for it.',
    ],
    407 => [
        'title' => 'Proxy Authentication Required',
        'detail' => 'You need to login to your proxy server first.',
    ],
    408 => [
        'title' => 'Request Timeout',
        'detail' => 'Your connection to the server took too long and it stopped waiting.',
    ],
    409 => [
        'title' => 'Conflict',
        'detail' => 'Your request cannot be completed because it conflicts with the current state of the server.',
    ],
    410 => [
        'title' => 'Gone',
        'detail' => 'The resource you are looking for has been permanently removed.',
    ],
    411 => [
        'title' => 'Length Required',
        'detail' => 'Your request did not say how much data it is sending and it needs to.',
    ],
    412 => [
        'title' => 'Precondition Failed',
        'detail' => 'The server could not meet the conditions you set in your request.',
    ],
    413 => [
        'title' => 'Content Too Large',
        'detail' => 'You are trying to send too much data at once.',
    ],
    414 => [
        'title' => 'URI Too Long',
        'detail' => 'The address you are using is too long.',
    ],
    415 => [
        'title' => 'Unsupported Media Type',
        'detail' => 'The server cannot handle the type of data you are sending.',
    ],
    416 => [
        'title' => 'Range Not Satisfiable',
        'detail' => 'You are asking for a part of the data that is not available.',
    ],
    417 => [
        'title' => 'Expectation Failed',
        'detail' => 'The server could not meet the expectations set in your request.',
    ],
    418 => [
        'title' => 'I\'m a teapot',
        'detail' => 'This is an internet joke from 1998.',
    ],
    421 => [
        'title' => 'Misdirected Request',
        'detail' => 'Your request was sent to the wrong server.',
    ],
    422 => [
        'title' => 'Unprocessable Content',
        'detail' => 'Your request contains invalid data and could not be processed.',
    ],
    423 => [
        'title' => 'Locked',
        'detail' => 'The resource you are trying to access is locked.',
    ],
    424 => [
        'title' => 'Failed Dependency',
        'detail' => 'Your request could not be completed because another request it depends on failed.',
    ],
    425 => [
        'title' => 'Too Early',
        'detail' => 'Processing your request is risky because it might be repeated.',
    ],
    426 => [
        'title' => 'Upgrade Required',
        'detail' => 'You need to use a different protocol to access this resource.',
    ],
    428 => [
        'title' => 'Precondition Required',
        'detail' => 'The server needs more information before it can fulfill your request.',
    ],
    429 => [
        'title' => 'Too Many Requests',
        'detail' => 'You are sending too many requests too fast, please slow down.',
    ],
    431 => [
        'title' => 'Request Header Fields Too Large',
        'detail' => 'Your request headers contain too much information.',
    ],
    451 => [
        'title' => 'Unavailable For Legal Reasons',
        'detail' => 'This resource is blocked for legal reasons.',
    ],
    500 => [
        'title' => 'Internal Server Error',
        'detail' => 'Something went wrong on the server.',
    ],
    501 => [
        'title' => 'Not Implemented',
        'detail' => 'The server does not understand the command you are trying to use.',
    ],
    502 => [
        'title' => 'Bad Gateway',
        'detail' => 'The server was trying to fulfill your request but got an invalid response.',
    ],
    503 => [
        'title' => 'Service Unavailable',
        'detail' => 'The server is currently unable to handle your request.',
    ],
    504 => [
        'title' => 'Gateway Timeout',
        'detail' => 'The server was trying to fulfill your request but did not get a response in time.',
    ],
    505 => [
        'title' => 'HTTP Version Not Supported',
        'detail' => 'The server does not support the version of the protocol you are using.',
    ],
    506 => [
        'title' => 'Variant Also Negotiates',
        'detail' => 'There is a configuration error within the server.',
    ],
    507 => [
        'title' => 'Insufficient Storage',
        'detail' => 'The server does not have enough space to complete your request.',
    ],
    508 => [
        'title' => 'Loop Detected',
        'detail' => 'The server got stuck in an infinite loop while processing your request.',
    ],
    510 => [
        'title' => 'Not Extended',
        'detail' => 'The server needs more information to fulfill your request.',
    ],
    511 => [
        'title' => 'Network Authentication Required',
        'detail' => 'You need to login to your network to access this resource.',
    ],
];
