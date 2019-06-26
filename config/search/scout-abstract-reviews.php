<?php

return [
    'searchableAttributes' => ['id'],

    'attributesForFaceting' => [
        'teacher_id',
        'year',
        'subject_id',
        'client_rating',
        'admin_rating',
        'final_rating',
        'reviewer_admin_id',
        'filterOnly(client_id)',
    ]
];
