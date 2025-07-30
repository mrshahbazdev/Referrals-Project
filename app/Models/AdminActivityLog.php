<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'log_type', 'action', 'description'];


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
