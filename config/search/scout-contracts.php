<?php

return [
    'searchableAttributes' => ['id'],

    'attributesForFaceting' => [
        'filterOnly(number)',
        'filterOnly(version)',
        'filterOnly(client_id)',
        'filterOnly(date_timestamp)',
        'filterOnly(created_at_timestamp)',

        'year',
        'grade_id',
        'created_email_id',
    ],

    'customRanking' => [
        'desc(date)',
    ],
];
