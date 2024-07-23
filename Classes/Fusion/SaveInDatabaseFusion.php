<?php
namespace NeosRulez\NodeSearch\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use NeosRulez\NodeSearch\Domain\Model\SearchTerm;
use NeosRulez\NodeSearch\Domain\Repository\SearchTermRepository;

class SaveInDatabaseFusion extends AbstractFusionObject
{

    /**
     * @Flow\Inject
     * @var SearchTermRepository
     */
    protected $searchTermRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @return void
     */
    public function evaluate(): void
    {
        if($this->fusionValue('saveInDatabase') && $this->fusionValue('searchterm') && $this->fusionValue('searchterm') !== '') {
            $newSearchTerm = new SearchTerm();
            $newSearchTerm->setSearchTerm($this->fusionValue('searchterm'));
            $newSearchTerm->setSearchCount($this->fusionValue('searchCount'));
            $this->searchTermRepository->add($newSearchTerm);
            $this->persistenceManager->persistAll();
        }
    }

}
