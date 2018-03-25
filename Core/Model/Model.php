<?php

namespace Jojotique\Framework\Model;

use Jojotique\Framework\Database\Database;
use Jojotique\Framework\ORM\Interfaces\ORMModelInterface;
use Jojotique\Framework\ORM\Classes\ORMModel;

/**
 * Classes Model
 * @package Jojotique\Framework\Model
 */
class Model extends ORMModel implements ORMModelInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var null
     */
    protected $table = null;

    /**
     * Model constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        parent::__construct($database->getPDO());
    }

    /**
     * Compte le nombre d'item dans la table.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->pdo->query("SELECT COUNT(id) FROM {$this->table}")->fetchColumn();
    }

    /** Getters - Setters */

    /**
     * @return null
     */
    public function getTable()
    {
        return $this->table;
    }
}
