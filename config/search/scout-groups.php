<?php
return [
    'searchableAttributes' => ['id'],

    'attributesForFaceting' => [
        'year',
        'teacher_id',
        'subject_id',
        'grade_id',
        'filterOnly(client_ids)'
    ],

    'customRanking' => [
        'desc(id)',
    ],
];
