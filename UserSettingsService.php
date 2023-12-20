<!-- app/Services/UserSettingsService.php -->

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserSettingsService
{
    private $user;
    private $emailService;
    private $codeGenerator;

    public function __construct(User $user, EmailService $emailService, ConfirmationCodeGenerator $codeGenerator)
    {
        $this->user = $user;
        $this->emailService = $emailService;
        $this->codeGenerator = $codeGenerator;
    }

    public function requestEmailChangeConfirmation($newEmail)
    {
        // Валидация данных
        $validator = Validator::make(['new_email' => $newEmail], [
            'new_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            // Логика обработки ошибок валидации, например, возврат с сообщением об ошибке
            return false;
        }

        $this->user->updateNewEmailAndConfirmationCode($newEmail);

        // Заглушка для отправки SMS
        $this->sendSms($newEmail, 'SMS Confirmation', 'SMS confirmation code: 1234');
        
        return true;
    }

    public function confirmEmailChange($confirmationCode)
    {
        $user = $this->user->getUserByEmailAndConfirmationCode($confirmationCode);

        // Проверка кода подтверждения
        if ($user) {
            $this->user->updateEmailAndResetNewEmail($user->new_email);

            return true;
        }

        return false;
    }

    public function requestPhoneNumberChangeConfirmation($newPhoneNumber)
    {
        // Валидация данных
        $validator = Validator::make(['new_phone_number' => $newPhoneNumber], [
            'new_phone_number' => 'required|numeric', // Ваше правило для номера телефона
        ]);
    
        if ($validator->fails()) {
            // Логика обработки ошибок валидации, например, возврат с сообщением об ошибке
            return false;
        }
    
        // Использование метода объекта User для выполнения запроса
        $this->user->updateNewPhoneNumberAndConfirmationCode($newPhoneNumber);
    
        // Получение пользователя
        $user = $this->user->findById($this->user->id);
    
        // Отправка кода подтверждения на электронную почту
        $this->emailService->sendEmail($user->email, 'Phone Number Confirmation', "Your confirmation code is: $user->phone_number_confirmation_code");
    
        return true;
    }

    public function confirmPhoneNumberChange($confirmationCode)
    {
        // Использование метода объекта User для выполнения запроса
        $user = $this->user->getUserByPhoneNumberAndConfirmationCode($confirmationCode);

        // Проверка кода подтверждения
        if ($user) {
            // Использование метода объекта User для выполнения запроса
            $this->user->updatePhoneNumberAndResetNewPhoneNumber($user->new_phone_number);

            return true;
        }

        return false;
    }

    // Остальные методы, если необходимо

    // ...
}
