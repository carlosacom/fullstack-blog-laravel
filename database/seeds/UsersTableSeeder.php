<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Category;
use App\Post;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'name' => 'Admin',
        ]);
        Role::create([
            'id' => 2,
            'name' => 'Comun'
        ]);
        User::create([
            'id' => 1,
            'name' => 'Carlos andres',
            'surname' => 'Perez',
            'role_id' => 1,
            'image'=> '',
            'email' => 'carlosaperez1997@gmail.com',
            'password' => 'a',
        ]);
        User::create([
            'id' => 2,
            'name' => 'victor',
            'surname' => 'robles',
            'role_id' => 2,
            'image'=> '',
            'email' => 'algo@algo.com',
            'password' => 'a',
        ]);
        Category::create([
            'id' => 1,
            'name' => 'Ordenadores'
        ]);
        Category::create([
            'id' => 2,
            'name' => 'Moviles y tablets'
        ]);
        Post::create([
            'id' => 1,
            'user_id' => 2,
            'category_id' => 1,
            'title' => 'Los ordenadores de Apple',
            'content' => 'Lorem, ipsum dolor sit amet consectetur ',
            'image' => ''
        ]);
        Post::create([
            'id' => 2,
            'user_id' => 1,
            'category_id' => 2,
            'title' => 'Los celulares de Apple',
            'content' => 'Lorem, ipsum dolor sit amet consectetur ',
            'image' => ''
        ]);
    }
}
