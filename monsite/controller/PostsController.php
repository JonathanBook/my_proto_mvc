<?php
class PostsController extends Controller
{

    function index()
    {
        /*(FR) Je défini le nombre de post par page
       (EN) I define the number of posts per page*/
        $perPage = 5;

        $this->loadModel('Post');

        $condition = array('online' => 1, 'type' => 'post');

        /*(FR) Renvoie tous les entrées de type post
        (EN) Returns all post type entries
 */
        $d['posts'] = $this->Post->find(array(
            'conditions' => $condition,
            'limit' => ($perPage * ($this->request->page - 1)) . ',' . $perPage
        ));
        /*
                            PAGINATION 
                        */
        /* (FR) Renvoie le nombre d'entrées qui porte le type post dans la base données
        (EN) Returns the number of entries with type post in the database */
        $d['total'] = $this->Post->findCount($condition);

        /* (FR)Calcul pour définir le nombre de postes par page
        (EN) Calculation to define the number of posts per page */
        $d['page'] = ceil($d['total'] / $perPage);


        $this->set($d);
    }

    /* (FR) Appelle la vue view qui est dans poste et prend en paramètre un ID et un Slug
    (EN) Calls up the view view which is in extension and takes an ID and a Slug as a parameter*/
    function view($id, $slug)
    {

        $this->loadModel('Post');
        /*(FR) Je récupère l'article stocker la base de donnée qui correspond à cette $id
        (EN) I get the article store the database that corresponds to this id */
        $d['post'] = $this->Post->findFirst(array(

            /*(FR) Je définis les champs que je veux récupérer
            (EN) I define the fields that I want retrieve */
            'fields'    => 'id,slug,content,name',

            /*(FR) Je définis mes conditions de recherche 
               (EN) I define my search conditions  */
            'conditions' => array('online' => 1, 'id' => $id, 'type' => 'post')
        ));


        if (empty($d['post'])) {
            $this->e404('page introuvable');
        }

        /*(FR) Si le slug qui est passer dans la requête url est différente de celle renvoyé par la base donnée je fais une redirection 301
          (EN) If the slug that is passed in the url request is different from that returned by the given database I make a 301 redirect */
        if ($slug != $d['post']->slug) {


            $this->redirect("posts/view/id:$id/slug:" . $d['post']->slug, 301);
        } else {
            /* (FR) Si les slug sont identiques j'envoie le contenu du poste à ma vue
               (EN) If the slug are identical I send the content of the post to my view  */
            $this->set($d);
        }
    }
    /**
     * ADMIN
     */

    function admin_index()
    {
   
    }

    function admin_post_index()
    {

        $perPage = 10;

        $this->loadModel('Post');

        /* (FR) Je définis mes conditions de recherche dans la base de données
        (EN)I define my search conditions in the database */
        $condition = array('type' => 'post');

        $d['posts'] = $this->Post->find(array(
            'fields' => 'id,name,online',
            'conditions' => $condition,
            'limit' => ($perPage * ($this->request->page - 1)) . ',' . $perPage
        ));
        /*
                            PAGINATION 
                        */
        /*(FR)  Je récupère dans la base de données les nombres d'entrées qui ont le type post
        (EN) I retrieve from the database the number of entries that have the post type */
        $d['total'] = $this->Post->findCount($condition);

        /*(FR) Je fais le calcul du nombre de poste que je répare page
        (EN) I calculate the number of positions I repair page  */
        $d['page'] = ceil($d['total'] / $perPage);

        $this->set($d);
    }
    /*(FR) SUPPRESSION ET AJOUT D'INFO DANS LA BASE DE DONNÉE POUR LES POST 
    (EN) DELETION AND ADDITION OF INFO IN THE DATABASE FOR POST 
  */
    /**
     * Permet de supprimer un article
     * @param id De l'article à supprimer
     * 
     */
    function admin_delete($id)
    {

        $this->loadModel('Post');
        $this->Post->delete($id);
        $this->Session->setFlash('Le contenu a bien été supprimé');
        $this->redirect('admin/posts/post_index');
    }

    /**
     * Permet d'éditer un article
     * @param string 'id'
     */
    function admin_post_edit($id = null)
    {

        $this->loadModel('Post');
        $d['id'] = '';
        /*(FR) On vérifie que notre variable objet data n'est pas vide
        (EN) We check that our data object variable is not empty  */
        if ($this->request->data) {

            /*(FR) On fait vérifier notre formulaire avant l'envoi dans la base de données
            (EN) We have our form checked before sending it to the database */
            if ($this->Post->validates($this->request->data, $this->Post->validate)) {
                /* PARTIE SAVE */
                $this->request->data->type = 'post';
                $this->request->data->created = date('Y-m-d h:i:s');
                $this->Post->save($this->request->data);
                $this->Session->setFlash('Le contenu a bien été modifié');
                $this->redirect('admin/posts/post_index');
            } else {
                /* PARTIE ERREUR VALIDATION DONNEE */
                $this->Session->setFlash('Merci de corriger vos information', 'bg-danger');
            }
        } else {

            if ($id) {

                $this->request->data  = $this->Post->findFirst(array(
                    'conditions' => array('id' => $id)
                ));
            }

            $d['id'] = $id;
        }

        $this->set($d);
    }


}
