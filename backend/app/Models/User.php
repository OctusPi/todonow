<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use App\Models\Contracts\Imodel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Imodel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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


    public function activateSecurity(): bool
    {
        return true;
    }

    public function modelName(): string
    {
        return 'tasks';
    }

    public function rules(): array
    {
        return [
            'name'     => 'required',
            'email'    => ['required', Rule::unique($this->table, 'email')->ignore($this->id)],
            'password' => 'required'
        ];
    }

    public static function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado!',
            'unique'   => 'Usuário com e-mail já cadastrado'
        ];
    }

    public function tasks():HasMany
    {
        return $this->hasMany(Task::class);
    }
}
