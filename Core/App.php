<?php

namespace Jojotique\Framework;

use App\Controller\AppController;
use Jojotique\Framework\Auth\DBAuth;
use Jojotique\Framework\Controller\Controller;
use Jojotique\Framework\Controller\ControllerInterface;
use Jojotique\Framework\Exception\JojotiqueException;
use Jojotique\Framework\Router\Route;
use Jojotique\Framework\Router\Router;
use Jojotique\ORM\Classes\ORMModel;
use Jojotique\ORM\Classes\ORMSelect;
use Jojotique\ORM\Classes\ORMSelectJoinTable;
use Psr\Container\ContainerInterface;
use Twig_Environment;

/**
 * Classes App
 * @package Jojotique\Framework
 */
class App
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var DBAuth
     */
    private $auth;

    /**
     * App constructor.
     * @param Router $router
     * @param ContainerInterface $container
     * @param DBAuth $auth
     */
    public function __construct(Router $router, ContainerInterface $container, DBAuth $auth)
    {
        $this->router = $router;
        $this->container = $container;
        $this->auth = $auth;
    }

    /**
     * Lance l'application
     *
     * Récupère le Router, lui fait trouver le bon controlleur, l'instancie et lance la vue
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];

        $route = $this->router->getRoute($uri);

        $controller = $this->newController($route);

        try {
            $controller->run($route->getNameMethod(), $route->getVars());
        } catch (JojotiqueException $e) {
            $_SESSION['flash'] = $e->getMessage();
            $controller->redirection('/error');
        }
    }

    /**
     * Récupère la route, le nom de la config pour les modèles et instancie le bon Controller
     *
     * @param Route $route
     * @return ControllerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function newController(Route $route): ControllerInterface
    {
        /** @var AppController $controller */
        $controller = $route->getController();

        // Récupération du nom de la Route pour permettre de récupérer le nom de la config pour les models
        $extractNameRoute = explode('_', $route->getName());
        $extractPathRoute = explode('/', $route->getPath());

        //Récupère l'utilisateur connecté
        $appController = $this->container->get(AppController::class);
        $appController->setSelect(new ORMSelect($this->container->get('orm.config')));
        $user = $_SESSION['user'];

        // Vérifie que si la route commence par un "admin", le User est bien connecté sinon le renvoie sur NotLog
        if ($extractPathRoute[1] === 'admin') {
            if (!$this->auth->logged($user)) {
                $this->container->get(Controller::class)->renderNotLog();
            }
        }

        // Pour la partie admin ajout du suffixe "admin" à la config
        if (isset($extractNameRoute[2])) {
            $models = $extractNameRoute[0] . '.' . $extractNameRoute[1] . '.models';
        } else {
            $models = $extractNameRoute[0] . '.models';
        }

        $models = (!empty($this->container->get($models))) ? $this->container->get($models) : [];

        return new $controller(
            $this->container->get(Twig_Environment::class),
            $this->container,
            $models,
            new ORMSelect($this->container->get('orm.config')),
            new ORMSelectJoinTable($this->container->get(ORMModel::class))
        );
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
}
