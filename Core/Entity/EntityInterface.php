<?php

namespace Jojotique\Framework\Entity;

use Jojotique\Framework\ORM\Classes\ORMTable;

interface EntityInterface
{
    /**
     * EntityInterface constructor.
     * @param ORMTable $ORMTable
     */
    public function __construct(ORMTable $ORMTable);
}
