<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table = 'registros';

    protected $fillable = [
        'titulo', 'descricao', 'endereco_texto',
        'lat', 'lng', 'status', 'id_user',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function fotos()
    {
        return $this->hasMany(Foto::class, 'id_registro');
    }

    public function logs()
    {
        return $this->hasMany(LogStatus::class, 'id_registro');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'PENDENTE'     => ['label' => 'Pendente',     'class' => 'badge-pendente'],
            'EM_ANDAMENTO' => ['label' => 'Em andamento', 'class' => 'badge-andamento'],
            'RESOLVIDO'    => ['label' => 'Resolvido',    'class' => 'badge-resolvido'],
            default        => ['label' => $this->status,  'class' => ''],
        };
    }
}
