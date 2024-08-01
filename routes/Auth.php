<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'ni', 'password', 'roles',
    ];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'ni');
    }
}
