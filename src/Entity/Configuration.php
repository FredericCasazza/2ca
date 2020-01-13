<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationRepository")
 */
class Configuration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=false, unique=true)
     */
    private $env;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $sessionMaxIdleTime = 0;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $recaptchaEnable = false;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recaptchaSiteKey;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recaptchaSecretKey;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mailerHost;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mailerPort;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $mailerEncryption;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $mailerAuthMode;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mailerUsername;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mailerPassword;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mailerTimeout;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $mailerSubjectPrefix;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mailerFrom;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEnv(): ?string
    {
        return $this->env;
    }

    /**
     * @param string $env
     * @return $this
     */
    public function setEnv(string $env): self
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @return int
     */
    public function getSessionMaxIdleTime(): int
    {
        return $this->sessionMaxIdleTime;
    }

    /**
     * @param int $sessionMaxIdleTime
     * @return Configuration
     */
    public function setSessionMaxIdleTime(int $sessionMaxIdleTime): self
    {
        $this->sessionMaxIdleTime = $sessionMaxIdleTime;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRecaptchaEnable(): bool
    {
        return $this->recaptchaEnable;
    }

    /**
     * @param bool $recaptchaEnable
     * @return $this
     */
    public function setRecaptchaEnable(bool $recaptchaEnable): self
    {
        $this->recaptchaEnable = $recaptchaEnable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecaptchaSiteKey(): ?string
    {
        return $this->recaptchaSiteKey;
    }

    /**
     * @param mixed $recaptchaSiteKey
     * @return $this
     */
    public function setRecaptchaSiteKey($recaptchaSiteKey): self
    {
        $this->recaptchaSiteKey = $recaptchaSiteKey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecaptchaSecretKey(): ?string
    {
        return $this->recaptchaSecretKey;
    }

    /**
     * @param mixed $recaptchaSecretKey
     * @return $this
     */
    public function setRecaptchaSecretKey($recaptchaSecretKey): self
    {
        $this->recaptchaSecretKey = $recaptchaSecretKey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerHost(): ?string
    {
        return $this->mailerHost;
    }

    /**
     * @param string|null $mailerHost
     * @return Configuration
     */
    public function setMailerHost(?string $mailerHost): Configuration
    {
        $this->mailerHost = $mailerHost;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMailerPort(): ?int
    {
        return $this->mailerPort;
    }

    /**
     * @param int|null $mailerPort
     * @return Configuration
     */
    public function setMailerPort(?int $mailerPort): Configuration
    {
        $this->mailerPort = $mailerPort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerEncryption(): ?string
    {
        return $this->mailerEncryption;
    }

    /**
     * @param string|null $mailerEncryption
     * @return Configuration
     */
    public function setMailerEncryption(?string $mailerEncryption): Configuration
    {
        $this->mailerEncryption = $mailerEncryption;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerAuthMode(): ?string
    {
        return $this->mailerAuthMode;
    }

    /**
     * @param string|null $mailerAuthMode
     * @return Configuration
     */
    public function setMailerAuthMode(?string $mailerAuthMode): Configuration
    {
        $this->mailerAuthMode = $mailerAuthMode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerUsername(): ?string
    {
        return $this->mailerUsername;
    }

    /**
     * @param string|null $mailerUsername
     * @return Configuration
     */
    public function setMailerUsername(?string $mailerUsername): Configuration
    {
        $this->mailerUsername = $mailerUsername;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerPassword(): ?string
    {
        return $this->mailerPassword;
    }

    /**
     * @param string|null $mailerPassword
     * @return Configuration
     */
    public function setMailerPassword(?string $mailerPassword): Configuration
    {
        $this->mailerPassword = $mailerPassword;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMailerTimeout(): ?int
    {
        return $this->mailerTimeout;
    }

    /**
     * @param int|null $mailerTimeout
     * @return Configuration
     */
    public function setMailerTimeout(?int $mailerTimeout): Configuration
    {
        $this->mailerTimeout = $mailerTimeout;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerSubjectPrefix(): ?string
    {
        return $this->mailerSubjectPrefix;
    }

    /**
     * @param string|null $mailerSubjectPrefix
     * @return Configuration
     */
    public function setMailerSubjectPrefix(?string $mailerSubjectPrefix): Configuration
    {
        $this->mailerSubjectPrefix = $mailerSubjectPrefix;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMailerFrom(): ?string
    {
        return $this->mailerFrom;
    }

    /**
     * @param string|null $mailerFrom
     * @return Configuration
     */
    public function setMailerFrom(?string $mailerFrom): Configuration
    {
        $this->mailerFrom = $mailerFrom;
        return $this;
    }

}
