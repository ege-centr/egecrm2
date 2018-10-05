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

function resourceCollection($data, $resourceClass)
{
    return new \Illuminate\Http\Resources\Json\AnonymousResourceCollection($data, $resourceClass);
}

/**
 * Remove certain keys from array
 */
function without($array, $without)
{
    return array_diff_key($array, array_flip($without));
}


function getName($entity)
{
    return implode(' ', [$entity->first_name, $entity->last_name]);
}


/**
 * old egecrm connections
 */
function dbEgecrm($table)
{
    return \DB::connection('egecrm')->table($table);
}
