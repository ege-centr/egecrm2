<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;
use App\Http\Resources\Admin\Light as AdminResource;

class Sms extends Model
{
    use HasCreatedAdmin;

    const DISABLE_LOGS = true;

    protected $fillable = ['id', 'is_secret'];
    public $timestamps = false;

    public function toArray()
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'is_secret' => $this->is_secret,
        ];
    }
}
