<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'rol',
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

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getName(): string
    {
        return $this->attributes['name'];
    }

    public function setName(string $name): void
    {
        $this->attributes['name'] = $name;
    }

    public function getEmail(): string
    {
        return $this->attributes['email'];
    }

    public function setEmail(string $email): void
    {
        $this->attributes['email'] = $email;
    }

    public function getPassword(): string
    {
        return $this->attributes['password'];
    }

    public function setPassword(string $password): void
    {
        // Usa setAttribute para que el cast "hashed" aplique el hash.
        $this->setAttribute('password', $password);
    }

    public function getRol(): string
    {
        return $this->attributes['rol'];
    }

    public function setRol(string $rol): void
    {
        $this->attributes['rol'] = $rol;
    }

    public function isAdministrador(): bool
    {
        return $this->getRol() === 'administrador';
    }

    public function isCliente(): bool
    {
        return $this->getRol() === 'cliente';
    }

    /**
     * Ruta de inicio según el rol del usuario autenticado.
     */
    public function rutaInicio(): string
    {
        return $this->isAdministrador()
            ? route('admin.dashboard', absolute: false)
            : route('cliente.dashboard', absolute: false);
    }
}
