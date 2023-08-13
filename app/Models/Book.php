<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// if no table defined, default table is books
class Book extends Model
{
    // you can define table name 
    protected $table = 'my_books';
}
