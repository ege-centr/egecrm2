<?php

return [
    'searchableAttributes' => ['client_id'],

    'attributesForFaceting' => [
        'teacher_id',
        'year',
        'subject_id',
        'client_rating',
        'admin_rating',
        'final_rating',
        'filterOnly(client_id)'
    ]
];
