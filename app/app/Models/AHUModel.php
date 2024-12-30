<?php

namespace MyApp\Models;

use Illuminate\Database\Eloquent\Model;
use MyApp\Casts\Hash;

class AHUModel extends Model
{
    protected $table = 'ahu_models';

    protected $casts = [
        'id' => Hash::class.':sha256',
    ];

//     public function projects()
//     {
//         return $this->hasMany(Project::class);
//     }
}
?>


