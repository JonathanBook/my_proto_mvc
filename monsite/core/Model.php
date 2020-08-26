<?php
class Model
{
    /* (FR) Va contenir la connexion à la base de données
    (EN) Will contain the connection to the database */
    static $connections = array();

    /*(FR) Contiendra le nom de la variable à laquelle je veux accéder dans mon fichier conf pour récuperait les infos de connection pour se connecter à la base de données
     (EN) Will contain the name of the variable that I want to access in my conf file to retrieve the connection info to connect to the database */
    public $conf = 'default';

    public $table = false;

    public $db;

    /*  (FR) Contiendra la clé primaire qui est une id la plupart du temps
     (EN) Will contain the primary key which is an id most of the time */
    public $primaryKey = 'id';
    public $id;

    public $errors = array();/* (FR)Une table dans laquelle on peut stocker les erreurs (EN) A table where you can store errors */
    public $form; //(FR) Pourra contenir un formulaire pour lui retourner des erreurs (EN) May contain a form to return error

    /* --------------------------------------------CONNECTION-------------------------------------------------------------------- */
    public function __construct()
    {
        /* (FR) On initialise quelques variables
       (EN) We initialize some variables */

        /* (FR)Si la table est vide je récupère le nom de la calsse de mon objet et je la passe en minuscule en y rajoutant un s a la fin 
       (EN) If the table is empty I get the name of the calsse of my object and I pass it in lowercase by adding an s at the end */
        if ($this->table === false) {
            $this->table = strtolower(get_class($this)) . 's';
        }

        /*(FR) Je récupère les infos de connexion pour accéder à ma base de données
        (EN) I get the connection info to access my database */
        $conf = conf::$databasses[$this->conf];

        /*(FR) Je vérifie si je suis déjà connecté à base de donée si oui j'arrête la lecture de mon construct ici
       (EN) I check if I am already connected to database if yes I stop reading my construct here */
        if (isset(Model::$connections[$this->conf])) {
            $this->db = Model::$connections[$this->conf];
            return true;
        }

        try {/* (FR) Si je ne suis pas connecté j'essaie de démarrer une connexion
                (EN) If I am not connected I try to start a connection */


            /*(FR) On démarre la connexion à base de données
            (EN) We start the connection to the database */
            $pdo = new PDO('mysql:host =' . $conf['host'] . ';dbname=' . $conf['database'] .
                ';', $conf['login'], $conf['password'], array((PDO::MYSQL_ATTR_INIT_COMMAND) => 'SET NAMES utf8'));

            /*(FR) J'ai configurer ma base de données un attribut pour qu'il renvoie les erreurs et les warning
            (EN) I configure my database an attribute so that it returns errors and warnings */
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

            /*(FR) Accessible de partout
                    (EN) Accessible from anywhere */

            /*(FR) Je stocke ma connexion dans la variable connexion
            (EN) I store my connection in the connection variable */
            Model::$connections[$this->conf] = $pdo;

            /*(FR) Accessible que dans ce fichiers
                (EN) Accessible only in this file */

            /* (FR) Je fais de même avec ma variable db
            (EN) I do the same with my variable db */
            $this->db = $pdo;

            /* (FR) On va capturer les erreurs et les warning
            (EN) We will capture errors and warnings */
        } catch (PDOException $e) {

            /* (FR)Si le debug est activé
            (EN) If debug is enabled */
            if (conf::$debug >= 1) {

                /* (FR) On affiche les erreur
                (EN) We display the errors */
                die($e->getMessage());
            } else {
                /* (FR) Si le debug est désactivé on  affiche juste un message
                (EN) If debug is disabled we just display a message */
                die('impossible de se Connecter a la base de donnée');
            }
        }
    }

    /* ------------------------------------------------------------------SHEARCH IN DATABASE------------------------------------------------ */
    /* (FR) Permet de chercher et récupérer des entrée dans la base donnée
    (EN) Allows search and retrieve entries in the given database */
    public function find($req)
    {

        /* (FR) Si des champs spécifiques sont recherchés on les inclus avec un select 
        (EN) If specific fields are sought, they are included with a select */

        $sql = 'SELECT ';

        /* (FR)Je vérifie si ma variable fields contient des infos
        (EN) I check if my fields variable contains info */
        if (isset($req['fields'])) {

            /* (FR) Si elle contient des infos je vérifie si c'est un tableau
            (EN) If it contains info I check if it is an array */
            if (is_array($req['fields'])) {

                /* (FR) Si c'est un tableau je récupère son contenu et je le stock dans une chaîne de caractère que je sépare une virgule
                (EN) If it is an array I get its content and I store it in a character string that I separate with a comma */
                $sql .= implode(', ', $$req['fields']);
            } else {

                /* (FR) Si ce n'est pas un tableau je te stock directement le contenu dans la variable
                (EN) If it is not an array I directly store the content in the variable */
                $sql .= $req['fields'];
            }
        } else { /*(FR) Si il n'y a pas de champs spécifique rechercher je remplace par une étoile pour dire que je vais tout récupérer 
            (EN) If there is no specific fields to search I replace with a star to say that I will recover everything */

            $sql .= '*';
        }
        /* (FR) Pour finir je complète requête a la base de donnée 
        (EN) Finally I complete the database request */
        $sql .= ' FROM ' . $this->table . ' as ' . get_class($this) . ' ';


        /* (FR) On construit nos conditions de recherche 
        (EN) We build our research conditions */

        /*(FR) On vérifie que notre variable conditions est déclaré et qu'elle n'est pas null
        (EN) We check that our conditions variable is declared and that it is not null */
        if (isset($req['conditions'])) {

            /*(FR) Je rajoute 'WHERE' à ma requête SQL pour a jouter mes conditions
            (EN) I add 'WHERE' to my SQL query for add my conditions */
            $sql .= 'WHERE ';

            /*(FR) Si ma variable conditions n'est pas un tableau
            (EN) If my conditions variable is not an array */
            if (!is_array($req['conditions'])) {

                /* (FR)Je rajoute directement la condition
                (EN) I add the condition directly */
                $sql .= $req['conditions'];
            } else {/*(FR) Si c'est un tableau (EN) If it's an array */

                $cond = array();

                foreach ($req['conditions'] as $_key => $_value) {

                    /*(FR) Faisons un peu de sécurité
                            (EN)Let's do some security*/

                    /*(FR) permet de se proteger de certaine injection html  
                    (EN) allows to protect from certain html injection  */

                    /* (FR)je vérifie que ces pas un chiffre
                    (EN) I check that these are not a number */
                    if (!is_numeric($_value)) {

                        /*(FR) Si c'est pas un chiffre je rajoute des guillemets de chaque côté du champs
                        (EN) If it is not a number I add quotes on each side of the field */
                        $_value = $this->db->quote($_value);
                    }

                    /*(FR) Si c'est un chiffre je le rajoute directement mon tableau
                    (EN)If it is a number I add it directly to my table */
                    $cond[] = "$_key=$_value";
                }
                /* (FR) Je vais rajouter un AND entre chaque élément de mon tableau avant de le rajouter à ma chaîne de caractère
                (EN) I'm going to add an AND between each element of my array before adding it to my character string */
                $sql .= implode(' AND ', $cond);
            }
        }
        if (isset($req['order'])) {
            $sql .= ' ORDER BY  ' . $req['order'];
        }
        /* (FR) Si dans ma table $req j'ai une clé qui s'appelle 'limit je rajoute 'LIMIT' a ma requete SQL
         pour definir le nombre resultat que je desire a la base de donée
         
         (EN) If in my $ req table I have a key called 'limit I add' LIMIT 'to my SQL
          request to define the number of results I want in the database */
        if (isset($req['limit'])) {
            $sql .= ' LIMIT ' . $req['limit'];
        }


        /* (FR) j'utilise la fonction prepare() de PDO pour préparer ma requête SQL et il me renvera un objet qui contient ma requête préparer
        (EN) I use PDO's prepare () function to prepare my SQL request and it will return an object that contains my prepare request  */

        $sql_pre = $this->db->prepare($sql);

        /* (FR) j'exécute ma requête SQL préparée
          (EN) I execute my prepared SQL request */
        $sql_pre->execute();

        /* (FR) j'utilise la fonction fetchAll()  pour me retourner un tableau en OBJET qui
         contiendra tous les résultats de la requête demandé à la base données 
         (EN) I use the function fetchAll () to return me an array in OBJECT which will contain
          all the results of the request requested from the database */
        return $sql_pre->fetchAll(PDO::FETCH_OBJ);
    }



    /*(FR) Renvoie le premier résultat d'une recherche SQL
    (EN) Returns the first result of an SQL search */
    public function findFirst($req)
    {
        return current($this->find($req));
    }

    /* (FR) Cette fonction renvoie le nombre de résultats qui correspond à notre recherche
    (EN) This function returns the number of results that match our search */
    public function findCount($conditions)
    {
        $res = $this->findFirst(array(
            'fields' => 'COUNT(' . $this->primaryKey . ') as count',
            'conditions' => $conditions
        ));
        return $res->count;
    }


    /* (FR) Permet de supprimer des entrées dans la base de données
    (EN) Delete entries in the database */
    public function delete($id)
    {

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} =$id";
        $this->db->query($sql);
    }

    /* (FR) Permet d'insérer ou de sauvegarder des entrées à base de données
    (EN) Allows you to insert or save database entries */
    public function save($data)
    {

        $key = $this->primaryKey;
        $fields = array();
        $d = array();
        if (isset($data->$key) &&  $data->$key == '') {

            unset($data->$key);
        }
        foreach ($data as $_key => $_value) {
            $fields[] = "$_key=:$_key";
            $d[":$_key"] = $_value;
        }

        if (isset($data->$key) && !empty($data->$key) && $data->$key != '') {
            $sql = 'UPDATE ' . $this->table . ' SET ' . implode(',', $fields) . ' WHERE ' . $key . '=:' . $key;

            $this->id = $data->$key;
            $action = 'update';
        } else {


            $sql = 'INSERT INTO ' . $this->table . ' SET ' . implode(',', $fields);
            $action = 'insert';
        }

        $pre = $this->db->prepare($sql);

        $pre->execute($d);

        if ($action == 'insert') {
            $this->id = $this->db->lastInsertId();
            return  $this->id;
        }
    }

    public function specialRequete($req, $data)
    {
        $pre = $this->db->prepare($req);
        $pre->execute($data);
        return  $pre->fetchAll(PDO::FETCH_OBJ);
    }

    public function connectQuery($req){
      $d= $this->db->query($req);
     /*   */
      return $d->fetchAll(PDO::FETCH_OBJ);;
    }
    /* -----------------------------------------------------------------FORM VALIDATION---------------------------------------------- */
    /* (FR) Sert à valider un formulaire 
    (EN) Used to validate a form */
    /**
     * @param data Les données à valider
     * @param string Les règles à appliquer
     */
    function validates($data, $regleValidate)
    {
        $errors = array();
        foreach ($regleValidate as $_key => $_value) {

            if (!isset($data->$_key)) {
                $errors[$_key] = $_value['message'];
            } else {
                if ($_key == 'name' && $_key['rule'] == 'notEmpty' && empty($data->$_key)) {

                    $errors[$_key] = $_value['message'];
                } elseif ($_key == 'slug' && !preg_match('/' . $_value['rule'] . '$/', $data->$_key)) {

                    $errors[$_key] = $_value['message'];
                }
            }
        }
        $this->errors = $errors;

        if (isset($this->Form)) {

            $this->Form->errors = $errors;
        }
        if (empty($errors)) {
            return true;
        }
        return false;
    }
}
