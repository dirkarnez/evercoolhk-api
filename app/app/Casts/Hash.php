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
            // public function get(Model $model, string $key, mixed $value, array $attributes): Address
            // {
            //     return new Address(
            //         $attributes['address_line_one'],
            //         $attributes['address_line_two']
            //     );
            // }
 
            // public function set(Model $model, string $key, mixed $value, array $attributes): string
            // {
            //     // return [
            //     //     'address_line_one' => $value->lineOne,
            //     //     'address_line_two' => $value->lineTwo,
            //     // ];
            //     return "344";
            // }

            public function get($model, string $key, $value, array $attributes) {
                return "12cvv" . $value;
            }

            public function set($model, string $key, $value, array $attributes){
                return "12cvv";
            }
        };
    }
}
?>