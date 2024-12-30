<?php

namespace MyApp\Casts;
 
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
 
class Hash implements Castable
{
    public static function castUsing(array $arguments): string
    {
        return "123x";
    }
}
?>