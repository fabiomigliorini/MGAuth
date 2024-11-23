<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'tblusuario';
    protected $primaryKey = 'codusuario';
    protected $fillable = [
        'usuario',
        'senha',
        'codfilial',
        'codpessoa',
        'impressoratelanegocio',
        'codportador',
        'impressoratermica',
        'ultimoacesso',
        'inativo',
        'impressoramatricial',
        'remember_token',
        'codimagem'
    ];

    protected $dates = [
        'alteracao',
        'criacao',
        'ultimoacesso',
        'inativo',
    ];


    public function findForPassport(string $username): User
    {
        
        $user = $this->where('usuario', $username)->first();
        if (empty($user)) {
            throw new \Exception('Usuário não encontrado');
        }
        return $user;
       
        //return $this->where('usuario', $username)->first();
    }


    public function getAuthPassword()
    {
        if (!empty($this->inativo)) {
            return null;
        }
 
        return $this->senha;
    }
}
