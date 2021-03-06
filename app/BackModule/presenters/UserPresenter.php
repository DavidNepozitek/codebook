<?php

namespace App\BackModule\Presenters;

use App\Components\IPasswordChangeFormFactory;
use App\Model\Entities\User;
use App\Model\UserModel;
use Grido\DataSources\Doctrine;
use Grido\Grid;
use Nette\Utils\Html;

class UserPresenter extends BasePresenter
{

    /** @var UserModel @inject */
    public $userModel;

    /** @var  IPasswordChangeFormFactory @inject */
    public $passwordChangeFormFactory;

    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole("admin")) {
            $this->redirect("Dashboard:default");
        }
    }

    protected function createComponentPasswordChangeForm()
    {
        return $this->passwordChangeFormFactory->create();
    }

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);

        $grid->setTemplateFile(__DIR__ . "/../templates/Grido/bootstrap.latte");
        $grid->getTranslator()->setLang('cs');

        $model = new Doctrine(
            $this->userModel->getEm()->createQueryBuilder()
                ->select("u")->from(User::class, "u")
        );

        $grid->model = $model;

        $grid->addColumnText('id', 'ID')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('email', 'E-mail')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('role', 'Role')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('changeRole', 'Akce')
            ->setCustomRender(function ($user) {

                $el = Html::el();

                $demote = Html::el("a");
                $demote->addAttributes(array("class" => "ajax btn btn--orange"));
                $demote->addText("Degradovat");
                $demoteIcon = Html::el("i");
                $demoteIcon->addAttributes(array("class" => "fa fa-level-down"));
                $demote->addHtml($demoteIcon);

                $promote = Html::el("a");
                $promote->addAttributes(array("class" => "ajax btn btn--blue"));
                $promote->addText("Povýšit");
                $promoteIcon = Html::el("i");
                $promoteIcon->addAttributes(array("class" => "fa fa-level-up"));
                $promote->addHtml($promoteIcon);

                $passChange = Html::el("a");
                $passChange->addAttributes(array("class" => "ajax pull-right btn btn--blue"));
                $passChange->addText("Změnit heslo");
                $passChange->href($this->link("User:passChange", ["id" => $user->getId()]));
                $passChangeIcon = Html::el("i");
                $passChangeIcon->addAttributes(array("class" => "fa fa-lock"));
                $passChange->addHtml($passChangeIcon);

                if ($user->getRole() == "guest") {
                    $demote->appendAttribute("class", "disabled");
                    $promote->href($this->link("ChangeRole!", ["id" => $user->getId(), "role" => "editor"]));
                }

                if ($user->getRole() == "editor") {
                    $demote->href($this->link("ChangeRole!", ["id" => $user->getId(), "role" => "guest"]));
                    $promote->href($this->link("ChangeRole!", ["id" => $user->getId(), "role" => "admin"]));
                }

                if ($user->getRole() == "admin") {
                    $demote->href($this->link("ChangeRole!", ["id" => $user->getId(), "role" => "editor"]));
                    $promote->appendAttribute("class", "disabled");
                }

                if ($this->presenter->getUser()->getIdentity()->getId() == $user->getId()) {
                    $demote = "";
                    $promote = "";
                    $passChange = "";
                }

                $el->addHtml($demote);
                $el->addHtml($promote);
                $el->addHtml($passChange);
                
                return $el;
            });
    }

    public function renderPassChange($id)
    {
        $this->template->id = $id;
    }

    /**
     * Changes a user's role
     * 
     * @param $id
     * @param $role
     */
    public function handleChangeRole($id, $role)
    {
        $user = $this->userModel->getOne(User::class, array("id" => $id));

        $user->setRole($role);
        $this->userModel->flush();

        if ($user->getRole() == $role) {
            $this->flashMessage("Role uživatele " . $user->getEmail() . " byla změněna na " . $user->getRole(), "success");
        }
    }


}