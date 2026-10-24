<?php

namespace App\Services\TelegramExchangeBot;

use App\Helper\EmailHelper;
use App\Helper\LangHelper;
use App\Repository\EmailsRepository;
use App\Service\EmailSenderService;
use App\Service\Processing;
use App\Service\RedisManager;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TelegramBotContainer
{
    /**
     * @var Processing $processing
     */
    protected $processing;

    /**
     * @var RedisManager $redisManager
     */
    protected $redisManager;

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var LangHelper $langHelper
     */
    protected $langHelper;

    /**
     * @var EmailHelper $emailHelper
     */
    protected $emailHelper;

    /**
     * @var EmailsRepository $emailRepository
     */
    protected $emailRepository;

    /**
     * @var EmailSenderService $emailSenderService
     */
    protected $emailSenderService;

    public function __construct(
        Processing $processing,
        RedisManager $redisManager,
        ContainerInterface $container,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        LangHelper $langHelper,
        EmailHelper $emailHelper,
        EmailsRepository $emailsRepository,
        EmailSenderService $emailSenderService
    ) {
        $this->processing   = $processing;
        $this->redisManager = $redisManager;
        $this->container    = $container;
        $this->translator   = $translator;
        $this->logger       = $logger;
        $this->langHelper   = $langHelper;
        $this->emailHelper  = $emailHelper;
        $this->emailRepository    = $emailsRepository;
        $this->emailSenderService = $emailSenderService;
    }

    /**
     * @return Processing
     */
    public function getProcessing(): Processing
    {
        return $this->processing;
    }

    /**
     * @return RedisManager
     */
    public function getRedisManager(): RedisManager
    {
        return $this->redisManager;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return EmailsRepository
     */
    public function getEmailRepository(): EmailsRepository
    {
        return $this->emailRepository;
    }

    /**
     * @return EmailHelper
     */
    public function getEmailHelper(): EmailHelper
    {
        return $this->emailHelper;
    }

    public function getEmailSenderService()
    {
        return $this->emailSenderService;
    }

    public function getLangHelper()
    {
        return $this->langHelper;
    }
}
