<?php
namespace NeosRulez\NodeSearch\Domain\Repository;

/*
 * This file is part of the NeosRulez.NodeSearch package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SearchTermRepository extends Repository
{

    protected $defaultOrderings = [
        'created' => QueryInterface::ORDER_DESCENDING,
    ];

}
