<?php

namespace Jojotique\Framework\Controller;

use Jojotique\Framework\Exception\JojotiqueException;

/**
 * Classes Controller
 * @package Jojotique\Framework\Controller
 */
interface ControllerInterface
{
    /**
     * Lit la méthode récupérée dans la route, vérifie que celle-ci est bien présente dans le contrôleur,
     * sinon renvoie une erreur.
     *
     * @param string $nameMethod
     * @param array|null $vars
     * @return void
     * @throws JojotiqueException
     */
    public function run(string $nameMethod, ?array $vars = []): void;

    /**
     * Renvoie vers la page 404
     *
     * @return void
     */
    public function render404(): void;

    /**
     * Renvoie vers la page de connexion
     *
     * @return void
     */
    public function renderNotLog(): void;

    /**
     * Renvoie vers une page d'erreur qui affiche que la page n'est accessible que pour les administrateurs
     *
     * @return void
     */
    public function renderErrorNotAdmin(): void;

    /**
     * Méthode de redirection, récupère le chemin en paramètre et renvoie.
     *
     * @param string $path
     * @return void
     */
    public function redirection(string $path): void;
}
