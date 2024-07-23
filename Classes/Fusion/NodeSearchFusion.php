<?php
namespace NeosRulez\NodeSearch\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use NeosRulez\NodeSearch\Domain\Service\NodeSearchService;

class NodeSearchFusion extends AbstractFusionObject
{

    /**
     * @Flow\Inject
     * @var NodeSearchService
     */
    protected $nodeSearchService;

    /**
     * @return array
     */
    public function evaluate(): array
    {
        $searchTerm = $this->fusionValue('searchterm');
        $currentNode = $this->fusionValue('currentNode');
        return $this->nodeSearchService->search($searchTerm, $currentNode);
    }

}
