<?php
namespace NeosRulez\NodeSearch\Fusion;

use Neos\ContentRepository\Domain\Model\Node;
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
        return $this->nodeSearchService->search($this->getSearchTerm(), $this->getCurrentNode(), $this->getSelectedNodeTypes());
    }

    /**
     * @return string
     */
    private function getSearchTerm(): string
    {
        return $this->fusionValue('searchterm');
    }

    /**
     * @return Node
     */
    private function getCurrentNode(): Node
    {
        return $this->fusionValue('currentNode');
    }

    /**
     * @return array|bool
     */
    private function getSelectedNodeTypes(): array|bool
    {
        return $this->fusionValue('selectedNodeTypes');
    }

}
