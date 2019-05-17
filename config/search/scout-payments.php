<?php

return [
    'searchableAttributes' => [
        'type',
        'method',
        'year',
        'category',
        'is_confirmed',
        'created_email_id',
        'date',
        'entity_id',
        'entity_type',
    ],

    'attributesForFaceting' => [
        'type',
        'method',
        'year',
        'category',
        'entity_type',
        'is_confirmed',
        'created_email_id',
    ],

    'customRanking' => [
        'desc(id)',
    ],
];
