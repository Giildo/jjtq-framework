<?php

namespace Jojotique\Framework\Controller;

use Jojotique\Framework\Auth\DBAuth;
use Jojotique\Framework\Exception\JojotiqueException;
use Jojotique\ORM\Classes\ORMSelect;
use Jojotique\ORM\Classes\ORMSelectJoinTable;
use Psr\Container\ContainerInterface;
use Twig_Environment;

/**
 * Classes Controller
 * @package Jojotique\Framework\Controller
 */
class Controller implements ControllerInterface
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var DBAuth
     */
    protected $auth;

    /**
     * @var ORMSelect
     */
    protected $select;

    /**
     * @var ORMSelectJoinTable|null
     */
    protected $jointTable;

    /**
     * Controller constructor.
     *
     * @param Twig_Environment $twig
     * @param ContainerInterface $container
     * @param array|null $models
     * @param ORMSelect $select
     * @param ORMSelectJoinTable|null $jointTable
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(Twig_Environment $twig, ContainerInterface $container, ?array $models = [], ?ORMSelect $select = null, ?ORMSelectJoinTable $jointTable = null)
    {
        $this->twig = $twig;
        $this->container = $container;

        if (!empty($models)) {
            $this->instantiationModels($models);
        }

        $this->auth = $this->container->get(DBAuth::class);
        $this->select = $select;
        $this->jointTable = $jointTable;
    }

    /**
     * Lit la méthode récupérée dans la route, vérifie que celle-ci est bien présente dans le contrôleur,
     * sinon renvoie une erreur.
     *
     * @param string $nameMethod
     * @param array|null $vars
     * @return void
     * @throws JojotiqueException
     */
    public function run(string $nameMethod, ?array $vars = []): void
    {
        if (is_callable([$this, $nameMethod])) {
            $this->$nameMethod($vars);
        } else {
            $className = get_class($this);
            throw new JojotiqueException("\"{$nameMethod}\" n'est pas une méthode de \"{$className}\"", JojotiqueException::ROUTE_METHOD_ERROR);
        }
    }

    /**
     * Renvoie vers la page 404
     *
     * @return void
     */
    public function render404(): void
    {
        header('HTTP/1.0 404 Not Found');
        header('Location: /404');
        die();
    }

    /**
     * Renvoie vers la page de connexion
     *
     * @return void
     */
    public function renderNotLog(): void
    {
        header('HTTP/1.1 301 Not Found');
        header('Location: /user/login');
        die();
    }

    /**
     * Renvoie vers une page d'erreur qui affiche que la page n'est accessible que pour les administrateurs
     *
     * @return void
     */
    public function renderErrorNotAdmin(): void
    {
        header('HTTP/1.1 301 Not Found');
        header('Location: /error/notAdmin');
        die();
    }

    /**
     * @uses Twig_Environment::render() : Fait le lien avec la fonction render de Twig
     *
     * @param string $nameView
     * @param array|null $twigVariable
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $nameView, ?array $twigVariable = []): void
    {
        echo $this->twig->render($nameView, $twigVariable);
    }

    /**
     * Méthode de redirection, récupère le chemin en paramètre et renvoie.
     *
     * @param string $path
     * @return void
     */
    public function redirection(string $path): void
    {
        header('HTTP/1.1 301 Not Found');
        header('Location: ' . $path);
        die();
    }

    /**
     * Récupère le tableau, envoyé par le container lors de la construction depuis le fichier de config.
     * Ajoute "Model" à la fin des noms et le chemin de création et créé les models.
     *
     * @param array $models
     * @return void
     */
    protected function instantiationModels(array $models): void
    {
        // Implémente les Models nécessaires récupérés depuis la config
        if (!empty($models)) {
            foreach ($models as $key => $model) {
                $key .= "Model";
                $this->$key = $model;
            }
        }
    }

    /** Getters - Setters */

    /**
     * @param ORMSelect $select
     */
    public function setSelect(ORMSelect $select): void
    {
        $this->select = $select;
    }
}
