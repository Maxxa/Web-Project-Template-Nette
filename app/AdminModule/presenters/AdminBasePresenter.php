<?php

namespace App\AdminModule\Presenters;

use App\DefaultModule\Presenters\BasePresenter;

class AdminBasePresenter extends BasePresenter{

    public function startup() {
        parent::startup();
        if (!$this->user->isLoggedIn()) {
            $this->redirect("Auth:sign");
        }
    }
}