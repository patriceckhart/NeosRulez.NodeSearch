<?php
namespace NeosRulez\NodeSearch\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class NodeSearchFusion extends AbstractFusionObject {

    /**
     * @Flow\Inject
     * @var \NeosRulez\NodeSearch\Domain\Service\NodeSearchService
     */
    protected $nodeSearchService;

    /**
     * @return void
     */
    public function evaluate() {
        $searchterm = $this->fusionValue('searchterm');
        $currentNode = $this->fusionValue('currentNode');
        $result = $this->nodeSearchService->search($searchterm, $currentNode);
        return $result;
    }

}
