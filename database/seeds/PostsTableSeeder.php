<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Para cada registro criado através da factory, irá percorrer com o each
          */
        factory(App\Post::class)->create()->each(function ($post){
            /**
             * E a cada registro na Model Post, com o relacionamento 'comments' 
             * irá através do saveMany  inserir 3 registros na Model Comment
             */
            $post->comments()->saveMany(
                factory(App\Comment::class, 3)->make()
            );

            /** 
             * Através do relacionamento com a model Category, 
             * irá salvar um registro na Category
             */
            $post->categories()->save(
                factory(App\Category::class)->make()
            );


            $post->details()->save(
                factory(App\Details::class)->make()
            );
            
        });
    }
}
