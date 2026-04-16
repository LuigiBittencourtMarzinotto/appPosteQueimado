<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatus extends Model
{
    protected $table = 'log_status';

    protected $fillable = ['id_registro', 'status_anterior', 'status_novo', 'id_admin'];

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'id_registro');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}
