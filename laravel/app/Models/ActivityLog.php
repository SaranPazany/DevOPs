<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['model', 'model_id', 'action', 'changes'];
    protected $casts = ['changes' => 'array']; // Ensure changes are stored as JSON
}
