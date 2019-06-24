<?php

namespace App\Traits;
use App\Models\{Client\Client, Teacher};

trait HasEntity
{
    public function entity()
    {
        return $this->morphTo();
    }

    public function scopeWhereClient($query, $clientId)
    {
        return $query
            ->where('entity_type', Client::class)
            ->where('entity_id', $clientId);
    }

    public function scopeWhereTeacher($query, $teacherId)
    {
        return $query
            ->where('entity_type', Client::class)
            ->where('entity_id', $teacherId);
    }
}
