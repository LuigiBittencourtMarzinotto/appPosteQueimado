<?php
// app/Models/Foto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table = 'fotos';

    protected $fillable = ['id_registro', 'caminho_arquivo', 'mime'];

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'id_registro');
    }
}
