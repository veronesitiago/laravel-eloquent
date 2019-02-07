<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $fillable = ['title', 'content'];

  /**
   * Mapeia o relacionamento com o model de posts
   *
   * @return void
   */
   public function post()
   {
     return $this->belongsTo('App\Post', 'post_id', 'id');
   }

   /**
    * Define titulo com primeira letra maiuscula
    * O mutador deve começar com set + nome_campo/coluna + Attribute
    * @return void
    */
   public function setTitleAttribute($value)
   {
      $this->attributes['title'] = ucfirst($value);
   }
}
