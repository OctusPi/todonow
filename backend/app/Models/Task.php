<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use App\Models\Contracts\Imodel;
use Illuminate\Database\Eloquent\Model;

class Task extends Model implements Imodel
{
    public function activateSecurity(): bool
    {
        return true;
    }

    public function modelName(): string
    {
        return 'task';
    }

    protected $table = 'tasks';

    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'user_id'
    ];

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                Rule::unique($this->table, 'title')->where(function ($query) {
                    return $query->where('user_id', $this->user_id);
            })->ignore($this->id)],
            'status'  => 'required',
            'user_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado!',
            'unique'   => 'Já existe uma tarefa com esse título...'
        ];
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function list_status():array
    {
        return ['Pendente', 'Finalizada'];
    }
}
