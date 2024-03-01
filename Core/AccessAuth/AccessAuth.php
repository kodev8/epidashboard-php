<?php

namespace Core\AccessAuth;

use Core\AccessAuth\Guest;
use Core\AccessAuth\Admin;

class AccessAuth {

    const ACCESS_MAP  = [
        'guest' => Guest::class,
        'admin' => Admin::class
    ];


    public static function resolveAccess($key) {

        if(!$key || empty(static::ACCESS_MAP[$key])) {
            return false;
        }

        $accessHandler = static::ACCESS_MAP[$key];

        (new $accessHandler) ->handleAccess();
    }
}