<?php
namespace NeosRulez\NodeSearch\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\DataSource\AbstractDataSource;

class NodeTypeDataSource extends AbstractDataSource {

    /**
     * @var string
     */
    protected static $identifier = 'neosrulez-nodesearch-nodetypes';

    /**
     * @Flow\Inject
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

    /**
     * @inheritDoc
     * @return array
     */
    public function getData(NodeInterface $node = null, array $arguments = array()): array
    {
        foreach ($this->getNodeTypes() as $nodeType) {
            $options[] = [
                'label' => array_key_exists('label', $nodeType->getConfiguration('ui')) ? $nodeType->getConfiguration('ui')['label'] : '',
                'value' => $nodeType->getName(),
                'icon' => array_key_exists('icon', $nodeType->getConfiguration('ui')) ? $nodeType->getConfiguration('ui')['icon'] : '',
            ];
        }
        return $options;
    }

    /**
     * @return ArrayCollection
     */
    private function getNodeTypes(): ArrayCollection
    {
        $nodeTypes = new ArrayCollection();
        foreach ($this->nodeTypeManager->getNodeTypes(false) as $nodeType) {
            if($nodeType->isOfType('Neos.Neos:Document')) {
                $nodeTypes->add($nodeType);
            }
        }
        return $nodeTypes;
    }

}
