<?php

return [
    'searchableAttributes' => ['id'],

    'attributesForFaceting' => [
        'filterOnly(entity_id)',
        'filterOnly(date_timestamp)',
        'type',
        'method',
        'year',
        'category',
        'entity_type',
        'is_confirmed',
        'created_email_id',
    ],

    'customRanking' => [
        'desc(date)',
    ],
];
