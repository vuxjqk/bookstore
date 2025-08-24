<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'entity_id',
        'details',
        'ip_address',
    ];
}
