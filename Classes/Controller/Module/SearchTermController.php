<?php
namespace NeosRulez\NodeSearch\Controller\Module;

/*
 * This file is part of the NeosRulez.NodeSearch package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Fusion\View\FusionView;
use NeosRulez\NodeSearch\Domain\Repository\SearchTermRepository;

class SearchTermController extends ActionController
{

    protected $defaultViewObjectName = FusionView::class;

    /**
     * @Flow\Inject
     * @var SearchTermRepository
     */
    protected $searchTermRepository;

    /**
     * @param int $offset
     * @param int $page
     * @param int $length
     * @return void
     */
    public function indexAction(int $offset = 0, int $page = 1, int $length = 50): void
    {
        $items = $this->searchTermRepository->findAll();

        $count = $items->count();

        $offset = $page > 1 ? (($page - 1) * $length) : $offset;

        $pages = ceil($count / $length);
        $pagination = [];
        if($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $pagination[] = $i;
            }
        }

        if($items->count() > 0) {
            $items = array_slice($items->toArray(), $offset, $length);
        }

        $this->view->assign('offset', ($offset + $length));
        $this->view->assign('length', $length);
        $this->view->assign('count', $count);

        $this->view->assign('pages', $pages);
        $this->view->assign('pagination', $pagination);
        $this->view->assign('page', $page);

        $this->view->assign('items', $items);
    }

    /**
     * @return void
     */
    public function deleteAllAction(): void
    {
        $this->searchTermRepository->removeAll();
        $this->redirect('index');
    }

}
