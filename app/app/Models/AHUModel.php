<?php

namespace MyApp\Models;

use Illuminate\Database\Eloquent\Model;

class AHUModel extends Model
{
    protected $table = 'ahu_models';

    protected $casts = [
        'year' => 'date:Y-m-d',
    ];

//     public function projects()
//     {
//         return $this->hasMany(Project::class);
//     }
}
?>
