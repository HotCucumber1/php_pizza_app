<?php

namespace App\Service;

class SiteUpdateManager
{
    public function __construct(MessageGenerator $generator, MailerInterface $mailer, private string $adminEmail)
    {
    }

}