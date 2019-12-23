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
}
