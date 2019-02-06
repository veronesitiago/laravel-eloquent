<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
}
