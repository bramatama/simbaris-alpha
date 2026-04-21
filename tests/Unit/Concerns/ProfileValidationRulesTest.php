<?php

use App\Concerns\ProfileValidationRules;

describe('ProfileValidationRules', function () {
    test('profileRules() returns array with name and email keys', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getProfileRules($userId = null)
            {
                return $this->profileRules($userId);
            }
        };

        $rules = $trait->getProfileRules();

        expect($rules)->toHaveKey('name');
        expect($rules)->toHaveKey('email');
    });

    test('nameRules() enforces name is required, string, max 255', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getNameRules()
            {
                return $this->nameRules();
            }
        };

        $rules = $trait->getNameRules();

        expect($rules)->toContain('required');
        expect($rules)->toContain('string');
        expect($rules)->toContain('max:255');
    });

    test('emailRules() enforces email is required, string, email, max 255', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getEmailRules($userId = null)
            {
                return $this->emailRules($userId);
            }
        };

        $rules = $trait->getEmailRules();

        expect($rules)->toContain('required');
        expect($rules)->toContain('string');
        expect($rules)->toContain('email');
        expect($rules)->toContain('max:255');
    });

    test('emailRules() includes unique rule when userId is null', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getEmailRules($userId = null)
            {
                return $this->emailRules($userId);
            }
        };

        $rules = $trait->getEmailRules(null);

        // Check that at least one rule is a Rule object (unique rule)
        $hasUniqueRule = false;
        foreach ($rules as $rule) {
            if (is_object($rule)) {
                $hasUniqueRule = true;
                break;
            }
        }

        expect($hasUniqueRule)->toBeTrue();
    });

    test('emailRules() without userId has 5 elements', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getEmailRules($userId = null)
            {
                return $this->emailRules($userId);
            }
        };

        $rules = $trait->getEmailRules(null);
        expect(count($rules))->toBe(5); // required, string, email, max:255, unique rule
    });

    test('emailRules() with userId has 5 elements', function () {
        $trait = new class {
            use ProfileValidationRules;

            public function getEmailRules($userId = null)
            {
                return $this->emailRules($userId);
            }
        };

        $rules = $trait->getEmailRules(123);
        expect(count($rules))->toBe(5); // required, string, email, max:255, unique with ignore rule
    });
});

