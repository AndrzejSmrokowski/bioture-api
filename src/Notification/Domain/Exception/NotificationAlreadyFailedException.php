<?php

declare(strict_types=1);

namespace Bioture\Notification\Domain\Exception;

final class NotificationAlreadyFailedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Notification has already failed.');
    }
}
