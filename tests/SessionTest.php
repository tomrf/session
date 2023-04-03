<?php

declare(strict_types=1);

use Tomrf\Session\Session;
use Tomrf\Session\SessionException;

/**
 * @internal
 *
 * @coversNothing
 */
final class SessionTest extends \PHPUnit\Framework\TestCase
{
    public static Session $session;

    public static function setUpBeforeClass(): void
    {
        self::$session = new Session();
        self::$session->startSession();
    }

    public function testSessionIsInstanceOfSession(): void
    {
        static::assertInstanceOf(Session::class, self::$session);
    }

    public function testSessionSetAndGet(): void
    {
        $data = [
            'string' => 'a string',
            'int' => 123,
            'float' => 1234.12345,
            'bool' => true,
            'array' => ['array' => 1],
            'object' => new stdClass(),
            'null' => null,
        ];

        foreach ($data as $key => $value) {
            $this->session()->set($key, $value);
            static::assertSame($value, $this->session()->get($key));
        }
    }

    public function testSessionClear(): void
    {
        $this->session()->set('k1', 1);
        $this->session()->set('k2', 'a');
        $this->session()->set('k3', false);

        $this->session()->clear();

        static::assertNull($this->session()->get('k1'));
        static::assertNull($this->session()->get('k2'));
        static::assertNull($this->session()->get('k3'));
    }

    public function testSessionCloseWrite(): void
    {
        $this->expectException(SessionException::class);
        $this->session()->closeWrite();
        $this->session()->set('testCloseWrite', true);
    }

    private function session(): Session
    {
        return self::$session;
    }
}
