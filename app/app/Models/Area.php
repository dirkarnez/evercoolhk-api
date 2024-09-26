<?php

namespace MyApp\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
?>
