<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;

class GroupClient extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id'];
    protected $primaryKey = null;
    public $incrementing = false;
}
