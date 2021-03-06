<?php

namespace App\Model;


use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;


class UserManager extends Object implements IAuthenticator {

    /** @var UserRepository */
    private $UR;

    /**
     * @param UserRepository $UR
     */
    public function __construct(UserRepository $UR) {
        $this->UR = $UR;
    }

    /**
     * Performs an authentication.
     * @param   $credentials array
     * @return  Identity
     * @throws  AuthenticationException
     */
    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;

        $user = $this->UR->findByUsername($username);

        if (!$user) {
            throw new AuthenticationException('Uživatel nenalezen.', self::IDENTITY_NOT_FOUND);
        }

        if (!Passwords::verify($password, $user->password)) {
            throw new AuthenticationException('Zadaná kombinace není platná.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($user->password)) {
            $user->password = Passwords::hash($password);
            $this->UR->persist($user);
        }
        return new Identity($user->id, NULL, ['username' => $user->username]);
    }

    /**
     * @param $username
     * @param $password
     * @return User
     */
    public function add($username, $password) {
        if ($this->UR->existsUsername($username)) {
            throw new InvalidArgumentException('Zadané uživatelské jméno již existuje.');
        }
        $user = new User;
        $user->username = $username;
        $user->password = Passwords::hash($password);
        $this->UR->persist($user);
        return $user;
    }
}

