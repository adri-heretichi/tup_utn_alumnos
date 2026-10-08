<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public const ROL_ALUMNO = 'alumno';

    public const ROL_DOCENTE = 'docente';

    public const ROL_BEDEL = 'bedel';

    protected $attributes = ['rol' => self::ROL_ALUMNO, 'is_admin' => false];

    public function esAlumno(): bool
    {
        return $this->rol === self::ROL_ALUMNO;
    }

    public function esDocente(): bool
    {
        return $this->rol === self::ROL_DOCENTE;
    }

    public function esBedel(): bool
    {
        return $this->rol === self::ROL_BEDEL;
    }

    public function nombreRol(): string
    {
        return match ($this->rol) {
            self::ROL_ALUMNO => 'Alumno',
            self::ROL_DOCENTE => 'Docente',
            self::ROL_BEDEL => 'Bedel',
            default => 'Sin rol reconocido',
        };
    }

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
        'phone',
        'professional_url',
        'photo_path',
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
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Retorna la URL directa a WhatsApp normalizando el teléfono con el código de país configurado.
     */
    public function obtenerEnlaceWhatsApp(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        // Limpiamos el teléfono dejando únicamente dígitos
        $soloNumeros = preg_replace('/[^0-9]/', '', $this->phone);

        if (empty($soloNumeros)) {
            return null;
        }

        $codigoPais = config('whatsapp.codigo_pais', '54');

        // Si el número ya comienza con el código de país configurado, no lo duplicamos
        if (str_starts_with($soloNumeros, $codigoPais)) {
            $numeroFinal = $soloNumeros;
        } else {
            $numeroFinal = $codigoPais.$soloNumeros;
        }

        return "https://wa.me/{$numeroFinal}";
    }

    /**
     * Retorna la URL pública de la foto de perfil o una imagen genérica predeterminada si no existe.
     */
    public function obtenerUrlFoto(): string
    {
        if ($this->photo_path) {
            return asset('storage/'.$this->photo_path);
        }

        // SVG Avatar predeterminado genérico limpio (sin consumir servicios externos ni fotos de terceros)
        return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%236B7280" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
    }
}
