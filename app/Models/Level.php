<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'upgrade_cost', 'daily_task_limit'];

    // Is level par kitne users hain
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
