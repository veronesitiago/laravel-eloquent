<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $date = ['deleted_at'];
    /**
     * Mapeia o relacionamento com o model details
     *
     * @return void
     */
    public function details()
    {
        return $this->hasOne('App\Details', 'post_id', 'id')
                    ->withDefault(function($details) {
                        $details->status = 'rascunho';
                        $details->visibility = 'privado';
                    });
    }

    /**
     * Mapeia o relacionamento com o model de comentários
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'post_id', 'id');
    }

    /**
     * Mapeia o relacionamento com o model de categorias
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_post', 'post_id', 'category_id')
        ->using('App\CategoryPost')
        ->withTimestamps();
                    // ->as('relacao')  // alias para utilizar o relacionamento, ->pivolt
                    // ->wherePivolt('active', 1); // Para filtrar o relacionamento, retornar somente quando active == 1
                    // ->withPivolt('username','active'); // acrescentar novas colunas a tab de relacionamento
    }

    /**
     * Retorna as avaliações relacionadas com o post
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

    /**
     * Verifica se post é aprovado
     *
     * Deve começar com a palavra reservada scope
     * @param [type] $query recebe uma varíavel que tem uma instância do QueryBuilder
     * @return void
     */
    public function scopeIsApproved($query)
    {
        return $query->where('approved', 1);
    }

}
