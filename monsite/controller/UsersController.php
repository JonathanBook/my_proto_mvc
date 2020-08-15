<?php
class UsersController extends Controller
{


    /**
     * Login
     * 
     */
    function login()
    {
        $this->theme = 'login_and_logout';
        /*(FR) Je vérifie si ma variable data n'est pas vide 
        (EN)I check if my data variable is not empty */
        if ($this->request->data) {

            /* (FR)Je récupère les données que ma variable data contient 
             (EN)I get the data that my data variable contains*/
            $data = $this->request->data;

            /*(FR) On encode le mot de passe avec le système de cryptage sha1
            (EN) We encrypt the password with the encryption system*/
            $data->password = sha1($data->password);

            $this->loadModel('User');

            /*(FR) Je cherche l'utilisateur dans la base de données 
            (EN) I'm looking for the user in the database*/
            $user = $this->User->findFirst(array(

                'conditions' => array('login' => $data->login, 'password' => $data->password)
            ));

            /* (FR)Je vérifie que l'utilisateur n'est pas déjà enregistré dans &_SESSION
            (EN) I verify that the user is not already registered in */
            if (!empty($user)) {
                if ($user->validate == 1) {
                    /*(FR) Si il n'est pas enregistré je le rajoute
                     (EN) If it is not registered I add it */
                    $this->Session->write('User', $user);
                    /*(FR) Si l'utilisateur est connecté
                     (EN)If the user is logged in*/
                    if ($this->Session->isLogged()) {

                        /* (FR)On va générai des cookie si l'utilisateur Coche  se souvenir de Moi */
                        if (isset($data->remember)) {

                            setcookie('login', $user->login, time() + 365 * 24 * 3600, null, null, false, true);
                            setcookie('password', $user->password, time() + 365 * 24 * 3600, null, null, false, true);
                        }

                        /*(FR) Je vérifie si il a le rôle d'admin
                             (EN)I check if he has the role of admin */
                        if ($user->role == 'admin') {

                            /*(FR)Si il a le rôle d'admin je le redirige vers la page d'administration
                             (EN) If he has the role of admin I redirect him to the administration page */
                            $this->redirect(conf::$admin_prefixe . '/');
                        } else {
                            /*(FR) Dans le cas contraire je le redirige vers la page d'accueil 
                             (EN) Otherwise I redirect it to the home page */
                            $this->redirect('pages/accueil');
                        }
                    }
                } else {
                    /* Conte nom activer */
                    $this->Session->setFlash("Votre Compé n'est pas activer", 'bg-danger', $user);
                  
                }
            } else {
                /* info incorrecte */
                $this->Session->setFlash('Votre login ou mot de passe est incorrecte', 'bg-danger', $user);
              
            }
            /*(FR) Et je supprime son mot de passe pour plus qu'il ne soit visible
            (EN) And I delete his password so that it is not visible */
            $this->request->data->password = '';
        }
    }

    /**
     * Logout
     * 
     */
    function logout()
    {
        
        setcookie('login', '',time()-3600);
        setcookie('password','', time()-3600);

        $this->theme = 'login_and_logout';
        unset($_SESSION['User']);
        $this->Session->setFlash('Vous éte déconnecté');


        $this->redirect('pages/accueil');
    }

    /* (FR)Charge la page qui permet de récupérait sont mot de passe */
    function ForgotPassword()
    {
        $this->theme = 'login_and_logout';
    }
    /* (FR)Charge la page qui permet de s'enregistré*/
    function register()
    {
        $this->theme = 'login_and_logout';
    }
    /* (FR)Fonction qui permet de sauvegarder un nouvelle utilisateur */
    function newUser()
    {
        $this->loadModel('User');
        if ($this->request->data) {

            $data = $this->request->data;
            /* (FR)On vérifie que tous les champs son remplie */
            if (empty($data->name) || empty($data->login) || empty($data->email) || empty($data->password) || empty($data->password2)) {

                $this->Session->setFlash('Veuillez remplire tous les champs', 'bg-danger', $data);

                $this->redirect('users/register');
            } else {
                /* (FR)On vérifie que les deux mot de passe soit identique  */
                if ($data->password != $data->password2) {
                    $this->Session->setFlash('Vos deux mot de passe ne sont pas identique ', 'bg-danger', $data);

                    $this->redirect('users/register');
                } else {
                    /* (FR)On cherche si l'adresse email existe déjà dans la base de donnée */
                    $emailExist = $this->User->find(array(
                        'conditions' => array('email' => $data->email),
                        'fields' => 'email'
                    ));
                    /* (FR)On cherche si le login existe déjà dans la base de donnée */
                    $loginExist = $this->User->find(array(
                        'conditions' => array('login' => $data->login),
                        'fields' => 'login'
                    ));

                    if (!empty($loginExist)) {/* (FR)si le login existe on revois un message d'erreur  */

                        $this->Session->setFlash('Ce login est deja utilisé par un autre utilisateur', 'bg-danger', $data);
                        $this->redirect('users/register');
                    } else {
                        /* Vérification que le login correspond bien ho cristaires demander  */
                        if (!preg_match('/^[a-zA-z0-9_]+$/', $data->login)) {

                            $this->Session->setFlash('Votre login contient des caractère non hotorizer ', 'bg-danger', $data);
                            $this->redirect('users/register');
                        } else {

                            if (!empty($emailExist)) {/* (FR)si l'adresse existe on revois un message d'erreur  */


                                $this->Session->setFlash('Cette adresse email est déjà utiliser', 'bg-danger', $data);

                                $this->redirect('users/register');
                            } else {

                                /* Vérification que l'email est correct ho niveau de son format */
                                if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {

                                    $this->Session->setFlash("Votre adresse email n'es pas valide", 'bg-danger', $data);
                                    $this->redirect('users/profil');
                                } else {
                                    /* (FR)si tout est correct on sauvegarde le nouvelle utilisateur  */
                                    /* (FR)Encodage du mos de passe pour sauvegarde */
                                    $data->password = sha1($data->password);

                                    $SaveUser = new stdClass();
                                    $SaveUser->login = $data->login;
                                    $SaveUser->name = $data->name;
                                    $SaveUser->password = $data->password;
                                    $SaveUser->email = $data->email;
                                    $SaveUser->role = 'user';
                                    /* (FR Génération de la ket de validation par email ) */
                                    $keyLength = 12;
                                    $key = "";
                                    for ($i = 1; $i < $keyLength; $i++) {
                                        $key .= mt_rand(0, 9);
                                    }
                                    $SaveUser->validatekey = $key;
                                    $SaveUser->avatar='default.jpg';
                                    $this->User->save($SaveUser);
                                    /* (FR)On envoi un email de demande de Confirmation */
                                    SendMail::sendEmail($data->email, "<a href='http://localhost/Site-Dragonnser/users/confirmation/email:" . $SaveUser->email . "/key:" . $SaveUser->validatekey . "'>Comfirmer Votre email </a>", 'Confirmation d\'inscription');
                                    $this->redirect('users/login');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /* (Activation du conte via email) */
    function confirmation($email = null, $key = null)
    {
        if ($email == null || $key == null) {
            $this->redirect('pages/accueil');
        } else {
            $this->loadModel('User');
            $d['user'] = $this->User->findFirst(array(
                'conditions' => array('validatekey' => $key, 'email' => $email)
            ));
            if (!empty($d['user'])) {
                $d['user']->validate = 1;
                $this->User->save($d['user']);

                $this->Session->setFlash('Votre Compte est bien activer', 'bg-danger');
                $this->redirect('pages/accueil');
            } else {

                $this->redirect('pages/accueil');
            }
        }
    }

    function profil($id = null)
    {
        $this->loadModel('User');
        $Userinfo = $_SESSION['User'];
        if (empty($Userinfo->login)) {
            $this->redirect('users/login');
        } else {

            $d['user'] = $this->User->findFirst(array(
                'conditions' => array('login' => $Userinfo->login, 'email' => $Userinfo->email)
            ));

            $this->set($d);
        }
    }

    function SaveProfilEdite()
    {
        $SaveUser = new stdClass();
        $this->loadModel('User');
        /* (FR)récupération de l'id de l'utilisateur */
        $oldUserinfo = $_SESSION['User'];
        $d['user'] = $this->User->findFirst(array(
            'conditions' => array('login' => $oldUserinfo->login, 'email' => $oldUserinfo->email)
        ));

        /* (FR)On récupère les nouvelle donnée envoyer par l'utilisateur*/
        $newUserinfo = $this->request->data;
        /* (Fr)Vérification du mot de passe */
        if (empty($newUserinfo->newmp2)) {
            $SaveUser->password = $d['user']->password;
        } else {
            if ($newUserinfo->newmp == $newUserinfo->newmp2) {
                $SaveUser->password = $newUserinfo->newmp;
            } else {
                $this->Session->setFlash('Vos deux mot de passe ne sont pas identique', 'bg-danger', $newUserinfo);
                $this->redirect('users/profil');
            }
        }
        /* (FR)Vérification du login */
        if ($oldUserinfo->login == $newUserinfo->login) {
            $SaveUser->login = $d['user']->login;
        } else {
            if (!preg_match('/^[a-zA-z0-9_]+$/', $newUserinfo->login)) {

                $this->Session->setFlash('Votre login contient des caractère non hotorizer ', 'bg-danger', $newUserinfo);
                $this->redirect('users/profil');
            } else {

                $SaveUser->login = $newUserinfo->login;
            }
        }
        /* (FR) Vérification de l'email */
        if ($oldUserinfo->email == $newUserinfo->email) {
            $SaveUser->email = $d['user']->email;
        } else {

            if (!filter_var($newUserinfo->email, FILTER_VALIDATE_EMAIL)) {

                $this->Session->setFlash("Votre adresse email n'es pas valide ", 'bg-danger', $newUserinfo);
                $this->redirect('users/profil');
            } else {
                $SaveUser->email = $newUserinfo->email;
            }
        }
        /* (FR) Gestion d'avatar */
  
        if (isset($_FILES['avatar'])) {
                    /* (FR) Je récupère le premier élément et je stocke dans la variable $temps */
            $temp = current($_FILES);
           

            /* (FR) Je vérifie que le fichier a été transmis par le HTTP POST */
            if (is_uploaded_file($temp['tmp_name'])) {

                /* (FR)Je vais vérifier que les extensions correspondent */
                if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {

                    $this->Session->setFlash("l'extension de votre fichier n'est pas autoriser sur ce site", 'bg-danger', $newUserinfo);
                    $this->redirect('users/profil');
                } else {
                   $dir= 'E:'.DS.'Monsite'.DS.'Site-Dragonnser' .DS . 'webroot' . DS . 'img' . DS .'membre'.DS.'avatars';
                    debug($dir);
                    /*(FR) Je définis le chemin où je vais enregistrer mon image et je la stock dans la variable $filetowrite*/
                    $filetowrite = $dir .  DS .$temp['name'];
                    /* (FR)Je déplacer fichier dans le dossier image*/
                   if( move_uploaded_file($temp['tmp_name'], $filetowrite)){

                    $SaveUser->avatar = $temp['name'];
                   }
             
                }
            }
        }
  
        $SaveUser->name = $newUserinfo->name;
        $SaveUser->role = $d['user']->role;
        $SaveUser->id = $d['user']->id;
        $this->User->save($SaveUser);
        $this->Session->setFlash('Vos info on était mie à jour');
        $this->redirect('pages/accueil');
    }
}
