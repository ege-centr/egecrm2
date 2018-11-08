<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;

class GroupClient extends Model
{
    const DISABLE_LOGS = true;
    public $timestamps = false;
    protected $fillable = ['group_id', 'client_id'];
    protected $primaryKey = null;
    public $incrementing = false;
}
