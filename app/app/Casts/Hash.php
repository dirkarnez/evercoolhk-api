<?php

namespace MyApp\Casts;
 
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
 
class Hash implements Castable
{
    public static function castUsing()
    {
        return new class implements CastsAttributes
        {
            public function get($model, string $key, $value, array $attributes) {
                return bin2hex($value);
            }

            public function set($model, string $key, $value, array $attributes){
                return "12cvv";
            }
        };
    }
}
?>