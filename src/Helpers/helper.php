<?php

if (! function_exists('session_get')) {

    function session_get($key){
        return $_SESSION[$key] ?? '';
    }

}

if (! function_exists('session_set')) {

    function session_set($key,$value){
        return $_SESSION[$key] = $value;
    }

}