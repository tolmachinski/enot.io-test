<!-- app/Services/ConfirmationCodeGenerator.php -->

<?php

namespace App\Services;

class ConfirmationCodeGenerator
{
    public function generateConfirmationCode()
    {
        // Логика генерации кода подтверждения
        return strtoupper(bin2hex(random_bytes(3)));
    }
}
