<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Rules\StrongPassword;

class StrongPasswordTest extends TestCase
{
    private StrongPassword $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new StrongPassword();
    }

     public function test_validate()
    {
        $passes = true;
        $this->rule->validate('password', 'Senha@123', function () use (&$passes) {
            $passes = false;
        });
        $this->assertTrue($passes);
    }

    public function test_short()
    {
        $failed = false;
        $this->rule->validate('password', 'Ab@1', function ($message) use (&$failed) {
            $failed = true;
            $this->assertStringContainsString('8 caracteres', $message);
        });
        $this->assertTrue($failed);
    }

    public function test_no_uppercase()
    {
        $failed = false;
        $this->rule->validate('password', 'senha@123', function ($message) use (&$failed) {
            $failed = true;
            $this->assertStringContainsString('maiúscula', $message);
        });
        $this->assertTrue($failed);
    }

    public function test_no_lowercase()
    {
        $failed = false;
        $this->rule->validate('password', 'SENHA@123', function ($message) use (&$failed) {
            $failed = true;
            $this->assertStringContainsString('minúscula', $message);
        });
        $this->assertTrue($failed);
    }

    public function test_no_number()
    {
        $failed = false;
        $this->rule->validate('password', 'Senha@abc', function ($message) use (&$failed) {
            $failed = true;
            $this->assertStringContainsString('número', $message);
        });
        $this->assertTrue($failed);
    }

    public function test_no_special_char()
    {
        $failed = false;
        $this->rule->validate('password', 'Senha123', function ($message) use (&$failed) {
            $failed = true;
            $this->assertStringContainsString('caractere especial', $message);
        });
        $this->assertTrue($failed);
    }

    public function test_multiple_fails()
    {
        $messages = [];
        $this->rule->validate('password', 'abc', function ($message) use (&$messages) {
            $messages[] = $message;
        });

        $this->assertCount(4, $messages);
        $this->assertStringContainsString('8 caracteres', $messages[0]);
        $this->assertStringContainsString('maiúscula', $messages[1]);
        $this->assertStringContainsString('número', $messages[2]);
        $this->assertStringContainsString('caractere especial', $messages[3]);
    }

}
