<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    // Allow role_name to create a new value to database.
    protected $fillable = ["role_name"];
}
