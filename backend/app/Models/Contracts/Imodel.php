<?php
namespace App\Models\Contracts;



interface Imodel
{
    public function activateSecurity(): bool;

    public function modelName(): string;

    public function rules(): array;

    public function messages(): array;
}
