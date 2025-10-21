<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',
        'is_online',
        'last_seen',
        'last_login_token',
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

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function get_gravatar_url(int $size = 200, string $default = 'mp', string $rating = 'g'): string
    {
        $email = $this->email ?? '';
        $email = trim(strtolower($email));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hash = str_repeat('0', 64);
        } else {
            $hash = hash('sha256', $email);
        }

        return sprintf(
            'https://gravatar.com/avatar/%s?s=%d&d=%s&r=%s',
            $hash,
            $size,
            urlencode($default),
            $rating
        );
    }

    public function getInitials(): string
    {
        $name = trim($this->name);
        if ($name === '') {
            return 'NA'; // Fallback jika nama kosong
        }

        $words = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);

        if (count($words) === 1) {
            return strtoupper(mb_substr($words[0], 0, 2)); // Ambil 2 huruf pertama jika hanya satu kata
        }

        // Ambil huruf pertama dari kata pertama dan terakhir
        $firstInitial = mb_substr($words[0], 0, 1);
        $lastInitial = mb_substr(end($words), 0, 1);

        return strtoupper($firstInitial . $lastInitial);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
