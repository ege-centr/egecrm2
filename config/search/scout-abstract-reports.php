<?php

return [
    'searchableAttributes' => [
        'year',
        'subject_id',
        'teacher_id',
        'client_id',
        'available_for_parents',
        'exists',
    ],

    'attributesForFaceting' => [
        'teacher_id',
        'year',
        'subject_id',
        'exists',
    ]
];
