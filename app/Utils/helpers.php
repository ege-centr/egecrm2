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

// function resourceCollection($data, $resourceClass)
// {
//     return new \Illuminate\Http\Resources\Json\AnonymousResourceCollection($data, $resourceClass);
// }

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

function shortName($entity)
{
    return $entity->last_name . ' ' . mb_substr($entity->first_name, 0, 1) . '. ' . mb_substr($entity->middle_name, 0, 1) . '.';
}

function getCurrentTime()
{
    return now()->format('Y-m-d H:i:s');
}

/**
 * old egecrm connections
 */
function dbEgecrm($table)
{
    return \DB::connection('egecrm')->table($table);
}

function dbEgerep($table)
{
    return \DB::connection('egerep')->table($table);
}

function getModelClass(string $class, bool $trim = false) : string
{
    $class = '\\App\\Models\\' . $class;
    if ($trim) {
        $class = trim($class, '\\');
    }
    return $class;
}

function trimModelClass($class)
{
    return substr($class, 11);
}

/**
 * Get model by class & id
 */
function getMorphModel($class, $entity_id) {
    $class = getModelClass($class);
    return $class::find($entity_id);
}

function getEntity($entity_type, $entity_id)
{
    $class = '\\' . $entity_type;
    return $class::find($entity_id);
}

function getUserByEmailId(int $emailId)
{
    return \App\Models\Email::find($emailId)->user;
}

/**
 * Текущий учебный год
 */
function academicYear($date = false) : int
{
    if ($date === false) {
        $date = now();
    }
    $year = date("Y", strtotime($date));
    $day_month = date("m-d", strtotime($date));

    if ($day_month >= '01-01' && $day_month <= '07-15') {
        $year--;
    }
    return intval($year);
}

function user()
{
    return \App\Models\User::fromSession();
}


function numToText($number)
{
    $f = new NumberFormatter("ru", NumberFormatter::SPELLOUT);
    return $f->format($number);
}

function extractFields($object, $fields, $merge = [])
{
    $return = [];
    foreach ($fields as $field) {
        $return[$field] = $object->{$field};
    }
    return array_merge($return, $merge);
}

/**
 * если указан $page, то имитируем до этой страницы
 */
function imitatePagination($items, $page = null) {
    return [
        'data' => $items,
        'meta' => [
            'current_page' => $page ? intval($page) : 1,
            'last_page' => $page ? 50 : 1,
        ],
    ];
}

function errorResponse($error, $status = 422)
{
    return response(compact('error'), $status);
}

/**
 * закодировать – раскодировать в JSON
 */
function jsonRedecode($data) {
    return json_decode(json_encode($data));
}
