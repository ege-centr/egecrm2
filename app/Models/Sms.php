<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;
use App\Http\Resources\Admin\Light as AdminResource;

class Sms extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['text', 'code', 'phone', 'external_id'];

    public function toArray()
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'text' => $this->text,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
