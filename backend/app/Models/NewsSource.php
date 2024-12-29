<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsSource extends Model
{
    
    protected $guarded = ['id'];

    public function articles(): HasMany {
        return $this->hasMany(Article::class, 'source_id');
    }

}
