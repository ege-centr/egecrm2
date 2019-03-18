<?php

$data = ['year', 'teacher_id', 'subject_id', 'grade_id'];

return [
    'searchableAttributes' => [implode(', ', $data)],
    'attributesForFaceting' => $data,
    'customRanking' => [
        'desc(id)',
    ],
    'attributesToHighlight' => [],
];
