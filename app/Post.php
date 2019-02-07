<?php

namespace App;

use App\Scopes\VisibleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Events\PostCreated;

class Post extends Model
{

    /**
     * Configura itens do model
     * Sobrescrever o metodo boot da classe pai
     *  Criar um escopo global que ira adicionar o orderby em todo consulta deste model
     * @return void
     */
    protected static function boot()
    {
        parent::boot(); // Executa o metodo da classe pai

        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        static::addGlobalScope(new VisibleScope);
    }

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $date = ['deleted_at'];

    protected $dispatchesEvents = [
        'created' => PostCreated::class // ::class passa o caminho completo da classe
    ];

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

    /**
     * Verifica o post com base no parametro dinamico
     *
     * @param [type] $query
     * @param [type] $approved
     * @return void
     */
    public function scopeApproved($query, $approved)
    {
        return $query->where('approved', $approved);
    }

    /**
     * Filtra posts com categorias realcionadas
     *
     * @param [type] $query
     * @return void
     */
    public function scopeHasCategories($query)
    {
        // categories é o nome do relacionamento entre a model post e categoria
        return $query->whereHas('categories');
    }

    /**
     * Limita a quantidade de caracteres
     *
     * @param [type] $value
     * @return void
     */
    public function getContentAttribute($value) 
    {
        return mb_strimwidth($value, 0, 30, "...");
    }
}
