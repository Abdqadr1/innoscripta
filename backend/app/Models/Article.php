<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'description', 'published_at', 'image_url', 'url', 'author_id', 'source_id'];

    protected $casts = [
        'published_at' => 'datetime'
    ];


    public function author():HasOne {
        return $this->hasOne(Author::class, 'author_id')->withDefault([
            'name' => ''
        ]);
    }

    public function source():HasOne {
        return $this->hasOne(NewsSource::class, 'source_id');
    }

    public function categories() : BelongsToMany {
        return $this->belongsToMany(Category::class, 'article_category', 'article_id', 'category_id')
                    ->withTimestamps();
    }

}
