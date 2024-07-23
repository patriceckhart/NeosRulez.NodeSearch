<?php
namespace NeosRulez\NodeSearch\Domain\Model;

/*
 * This file is part of the NeosRulez.NodeSearch package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class SearchTerm
{

    /**
     * @var string
     */
    protected $searchTerm;

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     * @return void
     */
    public function setSearchTerm(string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * @var int
     */
    protected $searchCount = 0;

    /**
     * @return int
     */
    public function getSearchCount(): int
    {
        return $this->searchCount;
    }

    /**
     * @param int $searchCount
     * @return void
     */
    public function setSearchCount(int $searchCount): void
    {
        $this->searchCount = $searchCount;
    }

    /**
     * @var \DateTime
     */
    protected $created;


    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

}
