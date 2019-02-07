<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Mapeia o relacionamento com a tabela de posts
     *
     * @return void
     */
    public function posts()
    {
        return $this->belongsTomany('App\Post', 'category_post', 'category_id', 'post_id')
        ->using('App\CategoryPost')
        ->withTimestamps();
                    // ->as('relacao')  // alias para utilizar o relacionamento, ->pivolt
                    // ->wherePivolt('active', 1); // Para filtrar o relacionamento, retornar somente quando active == 1
                    // ->withPivolt('username','active'); // acrescentar novas colunas a tab de relacionamento                    
    }
}
