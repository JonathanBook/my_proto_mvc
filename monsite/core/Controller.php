<?php
class Controller
{
    /* (FR)Déclaration des variables 
    (EN) Variable declaration*/
    public $request;
    private $vars = array();
    public $theme = 'default';
    private $rendered = false;

    /* (FR)Je récupère la requête url quand on éinitialise le contrôleur 
     (EN)We get the url request when we initialize the controller*/
    function __construct($request = null)
    {
        $this->Session = new Session();
        $this->Form = new Form($this);

        if ($request) {

            $this->request = $request;
            require ROOT . DS . 'config' . DS . 'hook.php';
        }
    }
    public function autoConnect()
    {
        /* On verifie que les Cookie existe */
        if (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
            /* On charge le model de la Table Users */
            $this->loadModel('User');
            /* On verifie si il ya bien un utilisateur avec ces info  */
            $user = $this->User->findFirst(array(

                'conditions' => array('login' => $_COOKIE['login'], 'password' => $_COOKIE['password'])
            )); 
            /* Si il ya bien un utlisateur on ouvre sa Session */
            if (!empty($user)) { 

              $this->Session->write('User', $user); 

            }
        }
       
    }
    /* (FR)Permet de faire un rendu de la page demandée
    (EN)Allows you to render the requested page */
    public function render($view)
    {
        /* FR(Connectera automatique ment lutilisateur si il a cocher la case se souvenir de moi ) */
        if (!isset($_SESSION['User'])) {
            $this->autoConnect();
        }

        /*(FR) Si la vue demandé est déjà rendu on retourne false
        (EN) If the requested view is already rendered we return*/
      /*   die(debug($this)); */
        if ($this->rendered || $this->request->controller == 'api') {
            return false;
        }

        /*(FR) On récupère les variable contenu dans notre table pour les utiliser dans notre vue 
        (EN)We retrieve the variables contained in our table to use them in our view*/
        extract($this->vars);

        /*(FR)Si URL commence par un '/' c'est que c'est une page d'erreur qui est demandé
           (EN)If URL begins with a '/' it is that an error page is requested */
        if (strpos($view, '/') === 0) {

            /*  (FR) On passe à notre vu le chemin vers le dossier erreur 
          (EN) We pass our way to the error folder */
            $view = ROOT . DS . 'view' . $view . '.php';
        } else {

            /* (FR)Si ce n'est pas une page d'erreur on récupère notre vue 
            (EN)If it is not an error page we get our view */
            $view = ROOT . DS . 'view' . DS . $this->request->controller . DS . $view . '.php';
        }


        /*(FR) ob_start arrête le rendu de la page par le navigateur
        (EN) ob_start stops the page rendering by the browser */
        ob_start();

        /*(FR) On charge la vu que on avait stocké dans la variable view
        (EN) We load the view that we had stored in the variable view */
        require($view);

        /*(FR)On lit les données qui on était stocker par ob_start et on les supprimes
        (EN) We read the data that we were storing by ob_start and we delete them */
        $content_for_theme = ob_get_clean();

        /*(FR) On ajoute le theme que on peut considerer comme le theme du site
        (EN) we add the theme which we can consider as the theme of the site */
        require ROOT . DS . 'view' . DS . 'theme' . DS . $this->theme . '.php';

        /*(FR) On definit rendered sur true pour signifier que la vue est rendu 
        (EN) We set rendered to true to signify that the view is rendered */
        $this->rendered = true;
    }

    /**
     * @param array qui contient les variables a passer a la vue
     *  @param var variable simple a passer a la vue
     * function qui renvoie les variables a la vue
     */
    public function set($key, $value = null)
    {
        /*(FR) Vérifie si la clé est un tableau
        (EN) Check if the key is an array */
        if (is_array($key)) {

            /*(FR) Ajoute directement les infos à la variable $vars 
            (EN) Add the info directly to the Vars variable */
            $this->vars += $key;
        } else {

            /*(FR) Si c'est pas une table je récupère juste la valeur de la clé
            (EN) If it's not a table I just get the value of the key */
            $this->vars[$key] = $value;
        }
    }

    /**
     * @param Le nom du modèle à charger
     * charge un modèle
     */
    function loadModel($name)
    {

        /*(FR) On commence par récupérer le modèle demander
        (EN) We start by recovering the requested model */
        $file = ROOT . DS . 'model' . DS . $name . '.php';

        /* (FR) Je charge le fichier
        (EN) I upload the file */
        require_once($file);

        /*(FR) Si la variable n'existe pas je initialise
        (EN) If the variable does not exist I initialize */
        if (!isset($this->$name)); {

            $this->$name = new $name();
        }

        /*(FR) Si ma variable n'est pas vide c'est qu'elle contient un formulaire donc je l'e passe à mon modèle
        (EN) If my variable is not empty it is because it contains a form so I pass it to my model */
        if (isset($this->Form)) {

            $this->$name->Form = $this->Form;
        }
    }

    /**
     * @param string un message 
     * affiche une page d'erreur de type 404
     */
    function e404($message)
    {
        $this->theme = 'error';
        /*(FR) Je renvoie une erreur de type 404 au navigateur
        (EN) I return a 404 browser error */
        header("HTTP/1.0 404 Not Found");

        /*(FR) On envoie le message à la vue
        (EN) We send the message to the view */
        $this->set('message', $message);

        /* (FR) J'affiche la page 404 qui est dans le dossier errors 
        (EN) I'm displaying page 404 which is in the errors folder */
        $this->render('/errors/404');

        /*(FR) J'arrête l'exécution du script
        (EN) I stop the execution of the script */
        die();
    }

    /**
     * @param controller 
     * @param action
     * Permet d'appeler un contrôleur depuis une vue
     */
    function request($controller, $action)
    {
        $controller .= 'Controller';
        require_once ROOT . DS . 'controller' . DS . $controller . '.php';
        $c = new $controller();
        return $c->$action();
    }

    /**
     * Redirection
     * @param url
     * @param code_de_redirection
     * Permet d'effectuer une redirection
     */
    function redirect($url, $code = null)
    {
        if ($code == 301) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location:" . Router::url($url));
    }
}
