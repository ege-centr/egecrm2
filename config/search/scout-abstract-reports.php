<?php

return [
    'searchableAttributes' => ['client_id'],

    'attributesForFaceting' => [
        'filterOnly(available_for_parents)',
        'filterOnly(client_id)',
        'teacher_id',
        'year',
        'subject_id',
        'exists',
    ]
];
