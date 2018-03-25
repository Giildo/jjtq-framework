<?php

namespace Core\Model;

use Core\Database\Database;
use Core\ORM\Interfaces\ORMModelInterface;
use Core\ORM\Classes\ORMModel;

/**
 * Classes Model
 * @package Core\Model
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