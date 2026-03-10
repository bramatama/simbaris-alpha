<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'role' => ['sometimes', 'string', 'in:admin,official_team,judge,commitee'],
        ])->validate();

        $user = User::create([
            'public_id' => Str::uuid(),
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => $input['role'] ?? 'official_team',
            'password' => $input['password'],
        ]);

        // Create role-specific record based on the selected role
        $role = $user->role;
        if ($role === 'admin') {
            $user->admin()->create();
        } elseif ($role === 'official_team') {
            $user->officialTeam()->create();
        } elseif ($role === 'judge') {
            $user->judge()->create();
        } elseif ($role === 'commitee') {
            $user->committee()->create();
        }

        return $user;
    }
}
