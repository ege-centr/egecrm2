<?php
return [
    'searchableAttributes' => ['id'],

    'attributesForFaceting' => [
        'status',
        'grade_id',
        'responsible_admin_id',
        'created_email_id',
        'filterOnly(created_at_timestamp)'
    ],

    'customRanking' => [
        'desc(id)',
    ],
];
