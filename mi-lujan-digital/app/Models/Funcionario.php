<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    public static function get()
    {
        return json_decode(file_get_contents(public_path('json/funcionario.json')));
    }
}
