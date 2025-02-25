<?php

if (!function_exists('assets')) {
    function assets($path) {
        return "/assets/" . ltrim($path, '/');
    }
}
