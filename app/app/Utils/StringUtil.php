<?php

namespace MyApp\Utils;
 
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
 
class StringUtil implements Castable
{
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, string $key, $value, array $attributes) {
                return bin2hex($value);
            }

            public function set($model, string $key, $value, array $attributes){
                return "TODO!!!!!";
            }
        };
    }
}
?>