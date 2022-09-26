<?php

namespace App\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\RawMessage;

/**
 * Interface MailManagerInterface.
 */
interface MailManagerInterface
{
    /**
     * Create default email enveloppe.
     *
     * @param string $recipientEmail
     *  Recipient e-mail address
     * @param array<string, mixed> $data
     *  Mail data
     * @param null|string $template
     *  Override default mail template
     */
    public function create(string $recipientEmail, array $data, ?string $template): TemplatedEmail;

    /**
     * Send a message.
     *
     * @param string $recipientEmail
     *  Recipient e-mail address
     * @param array|RawMessage $message
     *  Message to send
     * @param null|string $template
     *  Override default mail template
     */
    public function send(string $recipientEmail, mixed $message, ?string $template): void;
}

