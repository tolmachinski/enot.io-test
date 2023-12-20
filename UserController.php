<!-- app/Http/Controllers/UserController.php -->

<?php

namespace App\Http\Controllers;

use App\Services\UserSettingsService;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    private $user;
    private $userSettingsService;

    public function __construct(User $user, UserSettingsService $userSettingsService)
    {
        $this->user = $user;
        $this->userSettingsService = $userSettingsService;
    }

    public function showSettingsView($userId)
    {
        $user = $this->user->findById($userId);

        return view('user_settings', ['user' => $user]);
    }

    public function requestEmailChangeConfirmation(Request $request, $userId)
    {
        $newEmail = $request->input('new_email');

        $success = $this->userSettingsService->requestEmailChangeConfirmation($newEmail);

        if ($success) {
            return redirect()->route('user_settings', ['userId' => $userId])->with('success', 'Email change request sent.');
        } else {
            // Логика обработки ошибок, например, возврат с сообщением об ошибке
            return redirect()->route('user_settings', ['userId' => $userId])->withErrors(['request_failed' => 'Failed to request email change.']);
        }
    }

    public function confirmEmailChange(Request $request, $userId)
    {
        $confirmationCode = $request->input('confirmation_code');

        $success = $this->userSettingsService->confirmEmailChange($confirmationCode);

        if ($success) {
            return redirect()->route('user_settings', ['userId' => $userId])->with('success', 'Email successfully changed.');
        } else {
            // Логика обработки ошибок, например, возврат с сообщением об ошибке
            return redirect()->route('user_settings', ['userId' => $userId])->withErrors(['confirmation_code' => 'Invalid confirmation code.']);
        }
    }

    // Другие методы будет идентичны

    // ...
}
