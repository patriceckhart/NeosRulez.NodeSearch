<?php
namespace NeosRulez\NodeSearch\Domain\Service;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations;
use Neos\Neos\Domain\Service\NodeSearchService as NeosNodeSearchService;

/**
 * @Flow\Scope("singleton")
 */
class NodeSearchService {

    /**
     * @Flow\Inject
     * @var NeosNodeSearchService
     */
    protected $nodeSearchService;

    /**
     * @Flow\Inject
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @param string $searchParameter
     * @param Node $currentNode
     * @param array|bool|null $selectedNodeTypes
     * @return array
     */
    public function search(string $searchParameter, Node $currentNode, array|bool|null $selectedNodeTypes = false): array
    {
        $results = [];
        if($searchParameter !== '') {
            $nodes = $this->nodeSearchService->findByProperties($searchParameter, $this->getSearchabelNodeTypes(), $currentNode->getContext());
            foreach ($nodes as $node) {
                if($this->checkNodeType($node->getNodeType()->getName())) {
                    $properties = $node->getProperties();
                    $findString = '';
                    foreach ($properties as $property => $propetyName) {
                        if ($property != 'searchTags' && !is_object($propetyName) && is_string($propetyName) && (strpos($propetyName, $searchParameter) !== FALSE || strpos($propetyName, strtolower($searchParameter)) !== FALSE)) {
                            $findString = strip_tags($propetyName);
                            $findString = str_replace($searchParameter, '<strong>'.$searchParameter.'</strong>', $findString);
                            $findString = str_replace(strtolower($searchParameter), strtolower($searchParameter), $findString);
                            break;
                        }
                    }
                    if ((string) $node->getNodeType() !== 'Neos.Neos:Document') {
                        $flowQuery = new FlowQuery(array($node));
                        $documentNode = $flowQuery->closest('[instanceof Neos.Neos:Document]')->get(0);
                        if ($documentNode) {
                            $executeSearch = true;
                            if($selectedNodeTypes) {
                                if(in_array($documentNode->getNodeType()->getName(), $selectedNodeTypes, true)) {
                                    $executeSearch = true;
                                } else {
                                    $executeSearch = false;
                                }
                            }
                            if($executeSearch) {
                                $findString = $this->prepareFindString($findString, $searchParameter);
                                if (isset($results[$documentNode->getIdentifier()])) {
                                    if ($results[$documentNode->getIdentifier()]['findString'] < $findString) {
                                        $results[$documentNode->getIdentifier()] = array('findString' => $findString, 'documentNode' => $documentNode);
                                    }
                                } else {
                                    $results[$documentNode->getIdentifier()] = array('findString' => $findString, 'documentNode' => $documentNode);
                                }
                            }
                        }
                    }
                }
            }
        }
        return (count($results) > 0) ? $results : [];
    }

    /**
     * @return array
     */
    public function getSearchabelNodeTypes(): array
    {
        $nodeTypes = [];
        $fullConfiguration = $this->nodeTypeManager->getNodeTypes(false);
        foreach ($fullConfiguration as $key => $value) {
            $properties = $value->getProperties();
            if (!empty($properties)) {
                foreach ($properties as $property) {
                    if (isset($property['searchable'])) {
                        if ($property['searchable'] === true) {
                            $nodeTypes[] = $key;
                        }
                    }
                }
            }
        }
        return $nodeTypes;
    }

    /**
     * @param string $string
     * @param string $searchParameter
     * @return string
     */
    protected function prepareFindString(string $string, string $searchParameter): string
    {
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $partsCount = count($parts);
        $length = 0;
        $lastPart = 0;
        for (; $lastPart < $partsCount; ++$lastPart) {
            $length += strlen($parts[$lastPart]);
        }
        $findRegex = '/^.*'.$searchParameter.'.*$/';
        $findItems = preg_grep($findRegex, $parts);
        if (count($findItems) == 0) {
            $findRegex = '/^.*'.strtolower($searchParameter).'.*$/';
            $findItems = preg_grep($findRegex, $parts);
        }
        $keys = [];
        foreach ($findItems as $key => $value) {
            $keys[] = $key;
        }
        if (isset($keys[0])) {
            $start = $keys[0];
            if (($keys[0] - 20) > 0) {
                $start = $keys[0] - 20;
            }
            if (($start + 20) < (count($parts) - 1)) {
                $end = $start + 20;
            } else {
                $end = count($parts) - 1;
            }
        } else {
            $start = 0;
            $end = count($parts) - 1;
        }
        if (implode(array_slice($parts, $start, $end)) == '') {
            $findString = '';
        } else {
            if ($start == 0) {
                $findString = implode(array_slice($parts, $start, $end));
            } else {
                $findString = '... ' . implode(array_slice($parts, $start, $end));
            }
            if ($end != (count($parts) - 1)) {
                $findString = $findString . ' ...';
            }
        }
        return $findString;
    }

    /**
     * @param string $nodeType
     * @return boolean
     */
    public function checkNodeType(string $nodeType): bool
    {
        $result = true;
        if(array_key_exists('ignoredNodetypes', $this->settings)) {
            if(array_key_exists('values', $this->settings['ignoredNodetypes'])) {
                $ignoredNodetypes = $this->settings['ignoredNodetypes']['values'];
                foreach ($ignoredNodetypes as $ignoredNodetype) {
                    if($ignoredNodetype == $nodeType) {
                        $result = false;
                    }
                }
            }
        }
        return $result;
    }

}
