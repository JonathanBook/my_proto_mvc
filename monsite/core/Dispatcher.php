<?php

class Dispatcher
{
    var $request;

    function __construct()
    {
        /*(FR) On Initialise la classe request
        (EN) Initializes the request class */
        $this->request = new Request();

        /*(FR) Je demande au routeur de parse url
        (EN) I ask the router to parse url */
        Router::parse($this->request->url, $this->request);



        $controller = $this->loadController();

        /*(FR) Je stocke mon action au cas où on aurait un préfixe à rajouter plus tard
        (EN) I store my action in case we have a prefix to add later */
        $action = $this->request->action;

        /* GESTION DES ERREUR URL */

        /*(FR) On vérifie si on a un préfixe
        (EN) We check if we have a prefix */
        if ($this->request->prefix) {

            $action = $this->request->prefix . '_' . $action;
        }

        /*(FR) Je retire les méthodes de la classe parent
        (EN) I remove the methods from the parent class */
        if (!in_array($action, array_diff(get_class_methods($controller), get_class_methods('Controller')))) {

            /* (FR) si l'action demandée n'existe pas on initialise un message d'erreur pour l'utilisateur
            (EN) if the requested action does not exist we initialize an error message for the user */
            $this->error('Le controller ' . $this->request->controller . ' n\'a pas de méthod: ' . $action);
        }


        /*(FR)Nous allons appeler le contrôleur et exécuter  l'action demandée dans l'url
        (EN) We will call the controller and execute the action requested in the url */
        call_user_func_array(array($controller, $action), $this->request->params);

        /*(FR) Dans notre variable controller on passe à l'objet reder l'action demandée
        (EN) In our variable controller we go to the object reder the requested action */
        $controller->render($action);
    }
    /**
     * Envoie une erreur si l'url n'est pas correct
     * @param string le message
     */
    function error($message)
    {
        $controller = new Controller($this->request);
        $controller->e404($message);
    }


    /*(FR) Charge controller demandais dans l' url 
    (EN) Charge controller requested in url */
    function loadController()
    {
        /*(FR) On fait passer la première lettre du contrôleur en majuscule
        (EN) We pass the first letter of the controller in capital letters */
        $name = ucfirst($this->request->controller) . 'Controller';

        /*(FR) On stocke le PHP de notre contrôleur dans la variable file
        (EN) We store the PHP of our controller in the variable file */
        $file = ROOT . DS . 'controller' . DS . $name . '.php';

        /*(FR) On vérifie si le fichier PHP existe et que ce n'est pas un admin prefixe
        (EN) We check if the PHP file exists and that it is not an admin prefix */
    
        if(!file_exists($file) && $this->request->controller != conf::$admin_prefixe && $this->request->controller !='admin'){

            $this->error('Le controller '.$this->request->controller.' n\'existe pas');
        } 
        /*(FR) Si il existe on le charge
        (EN) If it exists we charge it */
        require $file;

        /*(FR) On initialise le contrôleur et on lui passe la variable request 
        (EN) Initialize the controller and pass it the request variable */
        $controller = new $name($this->request);

        /*(FR) Et on retourne notre contrôleur
        (EN) And we return our controller */
        return $controller;
    }
}
