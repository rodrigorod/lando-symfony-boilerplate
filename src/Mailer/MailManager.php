<?php

namespace App\Mailer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use UnexpectedValueException;

/**
 * Class MailManager.
 *
 * Handle e-mails creation & sending.
 */
class MailManager implements LoggerAwareInterface, MailManagerInterface
{
    use LoggerAwareTrait;

    public function __construct(
        protected MailerInterface $mailer,
        protected string $sender
    ) {}

    /**
     * {@inheritDoc}
     */
    public function create(string $recipientEmail, array $data, ?string $template): TemplatedEmail
    {
        if (null == $recipientEmail) {
            throw new UnexpectedValueException('Client has no registered email address.');
        }

        $emailTemplate = new TemplatedEmail();

        $emailTemplate
            ->from(new Address($this->sender))
            ->to($recipientEmail)
            ->textTemplate('@emails/default.txt.twig')
            ->htmlTemplate($template)
            ->context($data)
        ;

        if (isset($data['headers'])) {
            foreach ($data['headers'] as $name => $value) {
                $emailTemplate->getHeaders()->addTextHeader($name, $value);
            }
        }

        if (!empty($data['title'])) {
            $emailTemplate->subject($data['title']);
        }

        return $emailTemplate;
    }

    /**
     * {@inheritDoc}
     */
    public function send(string $recipientEmail, mixed $message, ?string $template): void
    {
        if (is_array($message)) {
            $message = $this->create($recipientEmail, $message, $template);
        }

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning(sprintf('MailManager::send - Error sending email: %s', $e->getMessage()));
        }
    }
}
