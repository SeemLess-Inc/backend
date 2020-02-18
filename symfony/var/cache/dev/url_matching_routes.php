<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/Api/CreateVideoEntry' => [[['_route' => 'app_api_media_createvideoentry', '_controller' => 'App\\Controller\\Api\\MediaController::createVideoEntry'], null, ['POST' => 0], null, false, false, null]],
        '/Api/CreateFrame' => [[['_route' => 'app_api_media_createframe', '_controller' => 'App\\Controller\\Api\\MediaController::CreateFrame'], null, ['POST' => 0], null, false, false, null]],
        '/Api/MediaConvert' => [[['_route' => 'app_api_media_convert', '_controller' => 'App\\Controller\\Api\\MediaController::Convert'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
