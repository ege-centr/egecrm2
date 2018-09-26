<?php

define('ONE_MONTH', 60 * 24 * 30);

function apiUrl($method)
{
    return "/api/v1/{$method}";
}

function cacheKey(...$args)
{
    return "egecrm:" . implode(':', $args);
}
