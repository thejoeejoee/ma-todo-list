<?php

namespace App\Components;


use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Model\UserManager;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User as NUser;
use Nette\Utils\ArrayHash;

interface IUserComponentFactory {
    /**
     * @param NUser $user
     * @return UserComponent
     */
    public function create(NUser $user);
}

/**
 * @method onLogin
 * @method onLogout
 */
class UserComponent extends BaseComponent {

    /** @var callable[] */
    public $onLogin;

    /** @var callable[] */
    public $onLogout;

    /** @var NUser */
    private $nUser;

    /** @var User|NULL */
    private $user = NULL;

    /** @var UserManager */
    private $UM;

    /** @var  UserRepository */
    private $UR;

    /**
     * @param NUser $nUser
     * @param UserManager $UM
     * @param UserRepository $UR
     */
    public function __construct(NUser $nUser, UserManager $UM, UserRepository $UR) {
        $this->UR = $UR;
        $this->UM = $UM;
        $this->nUser = $nUser;
        if ($nUser->isLoggedIn()) {
            $this->user = $this->UR->get($nUser->id);
        }
    }

    public function render() {
        parent::render();
    }

    /**
     * @return Form
     */
    public function createComponentLoginForm() {
        $f = new Form();
        $f->addText('username')->setRequired('Jméno opravdu musíš zadat!')
            ->controlPrototype->addAttributes(['placeholder' => 'Jméno']);
        $f->addPassword('password')->setRequired('Heslo musíš zadat!')
            ->controlPrototype->addAttributes(['placeholder' => 'Heslo']);
        $f->addSubmit('submit', 'Přihlásit');
        $f->onSuccess[] = function ($form, $values) {
            $this->loginFormSucceed($form, $values);
        };
        return $f;
    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     */
    public function loginFormSucceed(Form $form, ArrayHash $values) {
        try {
            $this->nUser->login($values['username'], $values['password']);
            $this->onLogin();
        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'warning');
        }
    }

    /**
     * @return Form
     */
    public function createComponentRegisterForm() {

        $f = new Form();
        $f->addText('username')->setRequired('Zadej i jméno.')
            ->controlPrototype->addAttributes(['placeholder' => 'Uživatelské jméno']);
        $f->addPassword('password', 'Heslo')->setRequired('Heslo je povinný prvek.')
            ->controlPrototype->addAttributes(['placeholder' => 'Heslo poprvé']);
        $check = $f->addPassword('password_check', 'Kontrola hesla');
        $check->setOmitted(TRUE)
            ->addConditionOn($f['password'], Form::FILLED)
            ->addRule(Form::FILLED, 'Zadej heslo ještě jednou pro ověření.')
            ->addRule(Form::EQUAL, 'Asi překlep, zkus to ještě jednou', $f['password']);
        $check->controlPrototype->addAttributes(['placeholder' => 'Heslo podruhé']);

        $f->addSubmit('submit', 'Přidat uživatele');
        $f->onSuccess[] = function (Form $form, ArrayHash $values) {
            $this->registerFormSucceed($form, $values);
        };
        return $f;
    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     */
    public function registerFormSucceed(Form $form, ArrayHash $values) {
        $this->UM->add(
            $values['username'],
            $values['password']
        );
        $form->setValues([], TRUE);
        $this->redrawControl();
        $this->flashMessage('Nový uživatel zaregistrován!', 'success');
    }

    public function handleLogOut() {
        $this->nUser->logout(TRUE);
        $this->onLogout();
    }
}