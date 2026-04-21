<?php

use App\Concerns\PasswordValidationRules;

describe('PasswordValidationRules', function () {
    test('passwordRules() returns array with required rules', function () {
        $trait = new class {
            use PasswordValidationRules;

            public function getPasswordRules()
            {
                return $this->passwordRules();
            }
        };

        $rules = $trait->getPasswordRules();

        expect($rules)->toBeArray();
        expect($rules)->toContain('required');
        expect($rules)->toContain('string');
        expect($rules)->toContain('confirmed');
        expect(count($rules))->toBeGreaterThanOrEqual(4); // includes Password::default()
    });

    test('currentPasswordRules() returns array with required rules', function () {
        $trait = new class {
            use PasswordValidationRules;

            public function getCurrentPasswordRules()
            {
                return $this->currentPasswordRules();
            }
        };

        $rules = $trait->getCurrentPasswordRules();

        expect($rules)->toBeArray();
        expect($rules)->toContain('required');
        expect($rules)->toContain('string');
        expect($rules)->toContain('current_password');
        expect(count($rules))->toBe(3);
    });
}
);
