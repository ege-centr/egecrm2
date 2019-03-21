<?php

$data = ['year', 'teacher_id', 'subject_id', 'grade_id'];

return [
    'searchableAttributes' => [implode(', ', $data)],
    'attributesForFaceting' => array_merge($data, ['filterOnly(client_ids)']),
    'customRanking' => [
        'desc(id)',
    ],
    'attributesToHighlight' => [],
];
