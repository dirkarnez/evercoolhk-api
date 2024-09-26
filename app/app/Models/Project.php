<?php

namespace MyApp\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $guarded = [];

    protected $casts = [
        'year' => 'date:Y-m-d',
    ];
    
    public function area()
    {
        return $this->belongsTo(Area::class, "area_id");
    }
}
?>
