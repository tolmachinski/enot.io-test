<!-- app/Models/User.php -->
<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\EmailService;
use App\Services\ConfirmationCodeGenerator;
use Illuminate\Validation\ValidationException;

class User extends Model
{
    protected $table = 'users';

    public function updateNewEmailAndConfirmationCode($newEmail)
    {
        // Валидация данных
        $this->validateNewEmail($newEmail);

        // Генерация кода подтверждения
        $confirmationCode = $this->codeGenerator->generateConfirmationCode();

        // Отправка кода подтверждения на электронную почту
        $this->emailService->sendEmail($newEmail, 'Email Confirmation', "Your confirmation code is: $confirmationCode");

        // Обновление данных в базе данных
        return DB::table($this->table)
            ->where('id', $this->id)
            ->update([
                'new_email' => $newEmail,
                'email_confirmation_code' => $confirmationCode,
            ]);
    }

    public function updateNewPhoneNumberAndConfirmationCode($newPhoneNumber)
    {
        // Валидация данных
        $this->validateNewPhoneNumber($newPhoneNumber);

        // Генерация кода подтверждения
        $confirmationCode = $this->codeGenerator->generateConfirmationCode();

        // Отправка кода подтверждения на электронную почту
        $this->emailService->sendEmail($this->email, 'Phone Number Confirmation', "Your confirmation code is: $confirmationCode");

        // Обновление данных в базе данных
        return DB::table($this->table)
            ->where('id', $this->id)
            ->update([
                'new_phone_number' => $newPhoneNumber,
                'phone_number_confirmation_code' => $confirmationCode,
            ]);
    }

    public function getUserByPhoneNumberAndConfirmationCode($confirmationCode)
    {
        // Использование Query Builder для выполнения запроса
        return DB::table($this->table)
            ->where('id', $this->id)
            ->where('phone_number_confirmation_code', $confirmationCode)
            ->first();
    }

    public function updatePhoneNumberAndResetNewPhoneNumber($newPhoneNumber)
    {
        // Валидация данных
        $this->validateNewPhoneNumber($newPhoneNumber);

        // Использование Query Builder для выполнения запроса
        return DB::table($this->table)
            ->where('id', $this->id)
            ->update([
                'phone_number' => $newPhoneNumber,
                'new_phone_number' => null,
                'phone_number_confirmation_code' => null,
            ]);
    }

    public function updateEmailAndResetNewEmail($newEmail)
    {
        // Валидация данных
        $this->validateNewEmail($newEmail);

        // Использование Query Builder для выполнения запроса
        return DB::table($this->table)
            ->where('id', $this->id)
            ->update([
                'email' => $newEmail,
                'new_email' => null,
                'email_confirmation_code' => null,
            ]);
    }

    private function validateNewPhoneNumber($newPhoneNumber)
    {
        // Валидация данных
        $validator = Validator::make(['new_phone_number' => $newPhoneNumber], [
            'new_phone_number' => [
                'required',
                'numeric', // Ваше правило для номера телефона
                Rule::unique($this->table, 'new_phone_number')->ignore($this->id),
            ],
        ]);

        if ($validator->fails()) {
            // Логика обработки ошибок валидации, например, возврат с сообщением об ошибке
            throw new ValidationException($validator);
        }
    }

    private function validateNewEmail($newEmail)
    {
        // Валидация данных
        $validator = Validator::make(['new_email' => $newEmail], [
            'new_email' => [
                'required',
                'email', // Ваше правило для электронной почты
                Rule::unique($this->table, 'new_email')->ignore($this->id),
            ],
        ]);

        if ($validator->fails()) {
            // Логика обработки ошибок валидации, например, возврат с сообщением об ошибке
            throw new ValidationException($validator);
        }
    }

    // Остальные методы, если необходимо
}
