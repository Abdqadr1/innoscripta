<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{

    protected $with = ['author'];
    protected $fillable = ['title', 'content', 'description', 'published_at', 'image_url', 'url', 'author_id', 'source_id'];

    protected $casts = [
        'published_at' => 'datetime'
    ];


    public function author():BelongsTo {
        return $this->belongsTo(Author::class, 'author_id')->withDefault([
            'name' => 'Unknown Author'
        ]);
    }

    public function source():BelongsTo {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

    public function categories() : BelongsToMany {
        return $this->belongsToMany(Category::class, 'article_category', 'article_id', 'category_id')
                    ->withTimestamps();
    }

}
