<?php
namespace App\AdminModule\Presenters;

use App\AdminModule\Model\UserManager;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;

class AuthPresenter extends Presenter {

    /**
     * @inject
     * @var UserManager
     */
    public $manager;

    public function actionAddUser($user, $pass, $name) {
        if ($user != NULL && $pass != NULL && $name != NULL) {
            $this->manager->add(["username" => $user, "password" => $pass, "name" => $name, "role" => "user"]);
        }
        $this->redirect("sign");
    }

    public function actionDefault() {
        $this->redirect("sign");
    }

    public function actionSign() {
        if ($this->user->isLoggedIn()) {
            $this->redirect("Artist:default");
        }
    }

    public function actionLogout() {
        $this->user->logout(TRUE);
        $this->redirect("sign");
    }

    public function createComponentLoginForm() {
        $form = new Form();

        $form->addText("username")->setRequired("E-mail is required!");
        $form->addPassword("password")->setRequired("Password is required!");
        $form->addSubmit("login");
        $form->onSuccess[] = $this->login;

        return $form;

    }

    public function login(Form $form, $values) {
        try {
            $this->user->login($values["username"], $values["password"]);
            $this->redirect("Artist:default");
        } catch (AuthenticationException $ex) {
            $form->addError("Špatné přihlašovací údaje.");
        }
    }

}