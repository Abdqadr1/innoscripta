<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function articles() : BelongsToMany {
        return $this->belongsToMany(Article::class, 'article_category', 'category_id', 'article_id')
                    ->withTimestamps();
    }

}
