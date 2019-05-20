<?php

return [
    'searchableAttributes' => [
        'number',
        'year',
        'grade_id',
        'client_id',
        'version',
        'created_email_id',
        'date_timestamp',
        'created_at_timestamp'
    ],

    'attributesForFaceting' => [
        'year',
        'grade_id',
        'created_email_id',
    ],

    'customRanking' => [
        'desc(date)',
    ],
];
