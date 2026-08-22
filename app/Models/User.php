<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Filters\V1\QueryFilter;
use App\Traits\HasProfilePhoto;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Order;
use App\Models\Review;
use App\Models\Post;
use App\Models\NewsLetter;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    use HasProfilePhoto;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'telephone',
        'phone_country_code',
        'email',
        'password',
        'status',
        'approve'
    ];

    protected $appends = [
        'profile_photo_url',
        'url'
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
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\QueuedResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\QueuedVerifyEmail);
    }


    /**
     * To filter by parameter
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('telephone', 'like', '%'.$search.'%');
            });
        })->when(
            $filters['sort_by'] ?? null,
            function ($query, $sort_by) use ($filters) {
                $query->orderBy($sort_by, $filters['sort_type'] ?? 'asc');
            }
        );
    }

    public function scopeApiFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function getUrlAttribute()
    {
        return base64_encode($this->id);
    }

    public function deleteData()
    {
        if($this->profile_photo_path){
            Storage::delete($this->profile_photo_path);
        }


        return true;
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function review() {
        return $this->hasMany(Review::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function newsletters() {
        return $this->hasMany(NewsLetter::class);
    }
}
