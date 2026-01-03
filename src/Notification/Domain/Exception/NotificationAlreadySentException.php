<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Exception;

final class NotificationAlreadySentException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Notification has already been sent.');
    }
}
