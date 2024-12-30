<?php

namespace MyApp\Casts;
 
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;
 
class Hash implements CastsInboundAttributes
{
    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return "123";
        // return is_null($this->algorithm)
        //             ? bcrypt($value)
        //             : hash($this->algorithm, $value);
    }
}
?>