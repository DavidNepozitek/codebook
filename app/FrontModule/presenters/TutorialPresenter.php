<?php

namespace App\FrontModule\Presenters;

use App\Components\IFilterFormFactory;
use App\Model\Entities\Tag;
use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette;


class TutorialPresenter extends BasePresenter
{

    /** @var TutorialModel @inject */
    public $tutorialModel;

    /** @var  IFilterFormFactory @inject */
    public $filterFormFactory;

    protected function createComponentFilterForm()
    {
        return $this->filterFormFactory->create();
    }


    public function renderDefault($search, $category, $difficulty = "-2", $page = 1)
    {
        $this->reloadFilter($search, $category, $difficulty, $page);
    }


    public function renderDetail($id)
    {
        $seenSection = $this->getSession("seen");

        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        if (isset($tutorial)) {
            if ($tutorial->getPublished() == FALSE) {
                throw new Nette\Application\BadRequestException;
            }

            $this->template->tutorial = $tutorial;

            if (!isset($seenSection->$id)) {

                $this->tutorialModel->seenIncrement($id);
                $seenSection->$id = TRUE;

                $seenSection->setExpiration("1 day", $id);
            }
        }
    }

    public function reloadFilter($search, $category, $difficulty, $page)
    {
        $this->template->search = $search;

        if (!isset($this->tutorialModel->difficulties[$difficulty]) AND $difficulty != -2) {
            $difficulty = -2;
        }
        $this->template->difficulty = $difficulty;

        $paginator = new Nette\Utils\Paginator();
        $paginator->setItemsPerPage(8);

        $this->template->paginator = $paginator;

        $q = $this->tutorialModel->getEm()->createQueryBuilder();
        $q->from('App\Model\Entities\Tutorial', 'tutorial');
        $q->where('tutorial.published = 1');

        if ($difficulty != "-2") {
            $q->andWhere('tutorial.difficulty = :difficulty');
            $q->setParameter("difficulty", $difficulty);
        }

        if (isset($category)) {
            $tag = $this->tutorialModel->getOne(Tag::class, array("name" => $category));

            if ($tag) {
                $this->template->category = $category;
                $q->andWhere(":tag MEMBER OF tutorial.tags");
                $q->setParameter("tag", $tag);
            } else {
                $this->template->category = NULL;
            }
        } else {
            $this->template->category = NULL;
        }

        if (isset($search)) {
            $q->andWhere("tutorial.perex LIKE :search OR tutorial.title LIKE :search");
            $q->setParameter("search", "%" . $search . "%");
        }

        $q->select('count(tutorial.id)');
        $count = $q->getQuery()->getSingleScalarResult();
        $paginator->setItemCount($count);

        if (isset($page) AND $page <= $paginator->getLastPage() AND $page >= 1) {
            $paginator->setPage($page);
        } elseif ($page > $paginator->getLastPage()) {
            $paginator->setPage($paginator->getLastPage());
        } else {
            $paginator->setPage(1);
        }

        $q->select('tutorial')->addOrderBy("tutorial.seenCount", "DESC");
        $q->setMaxResults($paginator->getItemsPerPage());
        $q->setFirstResult($paginator->getOffset());
        $tutorials = $q->getQuery()->getResult();

        $this->template->tutorials = $tutorials;
    }
}
