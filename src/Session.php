<?php

declare(strict_types=1);

namespace Tomrf\Session;

class Session
{
    private bool $isClosedForWriting = false;

    /**
     * Set a session key.
     */
    public function set(string $key, mixed $value): void
    {
        $this->startSessionIfNotStarted();
        $this->throwExceptionIfClosedForWriting();

        $_SESSION[$key] = $value;
    }

    /**
     * Get a session key value.
     *
     * @param null|mixed $default
     */
    public function get(string $key, $default = null): mixed
    {
        $this->startSessionIfNotStarted();

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Delete a session key.
     */
    public function delete(string $key): void
    {
        $this->startSessionIfNotStarted();
        $this->throwExceptionIfClosedForWriting();

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear the session.
     */
    public function clear(): void
    {
        $this->startSessionIfNotStarted();
        $this->throwExceptionIfClosedForWriting();

        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Close session for writing.
     */
    public function closeWrite(): void
    {
        $this->startSessionIfNotStarted();

        if (true !== session_write_close()) {
            throw new SessionException('Failed to close session for writing');
        }

        $this->isClosedForWriting = true;
    }

    /**
     * Start session.
     */
    public function startSession(): void
    {
        $this->startSessionIfNotStarted();
    }

    private function startSessionIfNotStarted(): void
    {
        if (PHP_SESSION_NONE !== session_status()) {
            return;
        }

        session_start();

        if (PHP_SESSION_ACTIVE !== session_status()) {
            throw new SessionException('Failed to start session');
        }
    }

    private function throwExceptionIfClosedForWriting(): void
    {
        if ($this->isClosedForWriting) {
            throw new SessionException('Unable to write to session, session is closed for writing');
        }
    }
}
