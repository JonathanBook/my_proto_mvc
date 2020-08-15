<?php

/* je definie une constante que japelle WEBROOTT qui va sotker l'url du dossier webroot
je recupaire cette url grace a la fonction dirname de php et je lui passe
 en parametre le super variable de php __FILE__ */
define('WEBROOTT', dirname(__FILE__));

define('DOC_ROOT',dirname( dirname($_SERVER['SCRIPT_FILENAME'])));

/*je remonte de un crant dans la structure des dossier et je recupaire le dossier racine */
define('ROOT', dirname(WEBROOTT));

/*je stock un element qui va me permetre de corriger un petit probleme de compatibiliter entre systmem
 car sur l'inux ces dees SLACH "\" et sur WIndows ces des ANTI SLACH "/"  et DIRECTORY_SEPARATOR 
 permet de corriger se probleme mes ces long a écrire donc je la 
 stocker dans une constate que je nome "DS"  */
define('DS', DIRECTORY_SEPARATOR);

/*je recupaire le chemin de mon dossier "core" et la stock dans une Constante que je nome "CORE"  */
define('CORE', ROOT . DS . 'core');

/* Je vais recuprait la base de mon Url on sait jamaist sapourait etre utile  */


define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME'])));
define('IMG_DOS', WEBROOTT . DS . 'img');
define('API_DOS', BASE_URL . DS . 'api');

//je recupaire mon fichier includes qui se trouve dans mon dossier Core
require CORE . DS . 'includes.php';

//jinitialise ma calss dispatcher
new Dispatcher();

?>

