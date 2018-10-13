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


function getName($entity, $full_name = false)
{
    if ($full_name) {
        $name_array = [$entity->last_name, $entity->first_name, $entity->middle_name];
    } else {
        $name_array = [$entity->first_name, $entity->last_name];
    }
    return implode(' ', $name_array);
}


/**
 * old egecrm connections
 */
function dbEgecrm($table)
{
    return \DB::connection('egecrm')->table($table);
}

function getModelClass($class) : string
{
    return '\\App\\Models\\' . $class;
}

/**
 * Get model by class & id
 */
function getMorphModel($class, $entity_id) {
    $class = getModelClass($class);
    return $class::find($entity_id);
}
