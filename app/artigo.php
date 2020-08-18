<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class artigo extends Model
{
    protected $fillable = ['titulo', 'mensagem','url'];

   
}
