<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Quando quiser usar mais de um db, tem que especificar na model
    // Adicionar no database.php e configurar o parametros no .env
    // protected $connection = 'sql_server';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Retorna as avaliações relacionadas com o usuario
     *
     * @return void
     */
    public function ratings()
    {
        /**
         * Colunas
         * ratingable_id
         * ratingable_type
         */
        return $this->morphMany('\App\Rating', 'ratingable');
    }

    public function testeWhereDinamico()
    {
        return $this->whereNameOrEmail('Fulano Silva', 'beltrano@gmail.com')->get();
    }
}
