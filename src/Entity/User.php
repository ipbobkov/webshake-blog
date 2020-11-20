<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="You already have an account!")
 */
final class User implements UserInterface
{
    public const GITHUB_OAUTH = 'Github';
    public const GOOGLE_OAUTH = 'Google';

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $clientId;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $oauthType;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $lastLogin;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     * 
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmationCode;

    /**
     * @param $clientId
     * @param string $email
     * @param string $username
     * @param string $oauthType
     * @param string $roles
     */
    public function __construct(
        $clientId,
        string $email,
        string $username,
        string $oauthType,
        string $roles
    ) {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->username = $username;
        $this->oauthType = $oauthType;
        $this->lastLogin = new DateTime('now');
        $this->roles = $roles;
    }

    /**
     * @param string $clientId
     * @param string $email
     * @param string $username
     *
     * @return User
     */
    public static function fromGoogleRequest(
        string $clientId,
        string $email,
        string $username
    ): User
    {
        return new self(
            $clientId,
            $email,
            $username,
            self::GOOGLE_OAUTH,
            self::ROLE_USER
        );
    }

    /**
     * @param int $clientId
     * @param string $email
     * @param string $username
     *
     * @return User
     */
    public static function fromGithubRequest(
        int $clientId,
        string $email,
        string $username
    ): User
    {
        return new self(
            $clientId,
            $email,
            $username,
            self::GITHUB_OAUTH,
            self::ROLE_USER
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getOauthType(): string
    {
        return $this->oauthType;
    }

    /**
     * @return DateTimeInterface
     */
    public function getLastLogin(): DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $arr = json_decode($this->roles);
        if(!is_array($arr) || !sizeof($arr)) $arr = array(self::ROLE_USER);

        return $arr;
    }
    
    /**
     * @param $roles
     *
     * @return $this
     */
    public function setRoles($roles): self
    {
        $this->roles = json_encode($roles);

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    
    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }


   /**
     * @return string
     */
    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    /**
     * @param string $confirmationCode
     *
     * @return User
     */
    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return User
     */
    public function setEnable(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}