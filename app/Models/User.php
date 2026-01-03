<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Principal\Usuario as UsuarioPrincipal;



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

    public function usuarioPrincipal()
    {
        return $this->hasOne(
            UsuarioPrincipal::class,
            'usuario_id',  // Foreign key en principal_usuarios
            'id'           // Local key en users (mismo valor que usuario_id)
        );
    }

public function tienePermiso(string $codigo): bool
{
    if (!$this->usuarioPrincipal) {
        return false;
    }

    $rol = $this->usuarioPrincipal->rol;

    if (!$rol) {
        return false;
    }

    // ADMIN TOTAL
    if ($rol->nombre === 'ADMINISTRADOR') {
        return true;
    }

    return $rol->permisos->contains(
        fn ($permiso) => $permiso->codigo === $codigo
    );
}

    
}
