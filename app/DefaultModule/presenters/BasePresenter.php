<?php

namespace App\DefaultModule\Presenters;

use App\Helpers\Lang;
use Nette;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter {

    /**
     * @persistent
     */
    public $lang;

    public function isLang($lang) {
        return $this->lang == $lang;
    }

    public function getLang() {
        return $this->lang == NULL || !$this->existLang() ? Lang::CZ : $this->lang;
    }

    protected function beforeRender() {
        parent::beforeRender();

        $this->template->lang = $this->getLang();
        $this->template->isLang = $this->isLang;
        $this->template->isEn = $this->isLang(Lang::EN);
        $this->template->isDe = $this->isLang(Lang::DE);
        $this->template->isRu = $this->isLang(Lang::RU);
        $this->template->isPl = $this->isLang(Lang::PL);
        $this->template->webPageName = $this->context->parameters['web-page'];
    }

    private function existLang() {
        $constant = Lang::getReflection()->constants;
        foreach ($constant as $lang) {
            if ($lang == $this->lang) {
                return TRUE;
            }
        }
    }

}
