<?php

namespace App\Rules;

use LaravelLegends\PtBrValidator\Rules\Cpf as BaseCpf;

class CustomCpf extends BaseCpf
{
    public function message()
    {
        return 'O campo CPF não é válido.'; // Custom message
    }
}
