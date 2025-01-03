<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_preference'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_preference' =>'boolean'
        ];
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class, 'author_user', 'author_id', 'user_id')
                    ->withTimestamps();
    }

    public function sources() : BelongsToMany {
        return $this->belongsToMany(NewsSource::class, 'news_source_user', 'news_source_id', 'user_id')
                    ->withTimestamps();
    }

    public function categories() : BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_user', 'category_id', 'user_id')
                    ->withTimestamps();
    }

}
