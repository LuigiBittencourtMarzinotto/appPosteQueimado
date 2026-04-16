<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = ['nome', 'email', 'password', 'tipo'];

    protected $hidden = ['password', 'remember_token'];

    public function registros()
    {
        return $this->hasMany(Registro::class, 'id_user');
    }
}
