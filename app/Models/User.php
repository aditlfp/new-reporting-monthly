<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    protected $connection = 'dbAbsensi';
    protected $table = 'users';

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function kerjasama()
    {
        return $this->belongsTo(Kerjasama::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'devisi_id', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function fixedImages()
    {
        return $this->hasMany(FixedImage::class);
    }


    public function isAccess(): bool
    {
        $jabatan = $this->jabatan;

        if (!$jabatan) {
            return false;
        }

        return Str::contains(
            strtolower($jabatan->type_jabatan . ' ' . $jabatan->name_jabatan),
            ['leader', 'manajemen', 'supervisor wilayah', 'supervisor area', 'supervisor pusat']
        ) || Str::contains(
            strtoupper($jabatan->code_jabatan),
            ['CO-CS', 'CO-SCR']
        );
    }

    public function canAccess(): bool
    {
        $jabatan = $this->jabatan;

        if (!$jabatan) {
            return false;
        }

        return Str::contains(
            strtolower($jabatan->type_jabatan . ' ' . $jabatan->name_jabatan),
            ['manajemen', 'supervisor wilayah', 'supervisor area', 'supervisor pusat']
        );
    }
}
