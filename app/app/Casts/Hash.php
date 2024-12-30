<?php

namespace MyApp\Casts;
 
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
 
class Hash implements Castable
{
    public static function castUsing(array $arguments): string
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

            public function get($model, string $key, $value, array $attributes);

            /**
             * Transform the attribute to its underlying model values.
             *
             * @param  \Illuminate\Database\Eloquent\Model  $model
             * @param  string  $key
             * @param  mixed  $value
             * @param  array  $attributes
             * @return mixed
             */
            public function set($model, string $key, $value, array $attributes);
        };
    }
}
?>