<?php
class MediasController extends Controller
{
    function galerie($id=null){
        if( $id == null) {
            $this->redirect('pages/galerie');
        }else{
            $this->loadModel('Media');

            $d['galerie'] = $this->Media->find(array(
                'conditions'=>array('id_theme'=>$id,'isgalerie'=>1),
                'fields'=>'url'
            ));
            if(empty($d['galerie'])){
                $this->redirect('pages/galerie');
            }else{
                
                $this->set($d);
            }
     

        }

    }

    function admin_index()
    {
        $this->theme = 'modal';
        $this->loadModel('Media');
        $d['images'] = $this->Media->find(array());
        $this->set($d);
    }

/* (FR)Suppression d'une image dans la base de donnée est le dossier image */
    function admin_delete($id)
    {
        $this->loadModel('Media');
        /* (FR) On récupère les info sur l'image dans la BD */
        $media = $this->Media->findFirst(array(
            'conditions' => array('id' => $id)

        ));
        /* (FR)Suppression de l'image du dossier image */
        unlink(WEBROOTT . DS . 'img' . DS . $media->file);
        /* (FR)Suppression des info de l'image dans la BD */
        $this->Media->delete($id);
        /* (FR)On fait un message pour dire que la suppression a réussite 
        puis on redirige verre la galerie */
        $this->Session->setFlash("Votre image est bien Supprimer");
        $this->redirect('admin/medias/galerie/');
    }

    function admin_save($id = '')
    {
        $this->loadModel('Media');
        if ($id == '') {
            reset($_FILES);
            $temp = current($_FILES);
            /* (FR)Je vais vérifier que les extensions correspondent */
            /* (EN)I will check that the extensions match */
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            /* (FR)Récupération de la date courante
            (FN)Retrieving the current date */
            $date = (date('Y,m'));
            $temp_date = explode(',', $date);
            $date = $temp_date[0] . DS . $temp_date[1];
            $dir = WEBROOTT  . DS . 'img' . DS . $date;



            /*(FR)Vérifie si le dossier existe
             (EN)Check if the folder exists */
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            /*(FR) Je définis le chemin où je vais enregistrer mon image et je la stock dans la variable $filetowrite*/
            /* (EN)I define the path where I will save my image and I store it in the variable $filetowrite */
            $filetowrite = $dir .  DS . $temp['name'];

            /*(FR)Vérifie si le fichier existe
            (EN) Check if the file exists */
            if (!file_exists($dir . DS . $temp['name'])) {

                /* (FR)Je déplacer fichier dans le dossier image
                (EN) I move file to image folder */
                move_uploaded_file($temp['tmp_name'], $filetowrite);

                /* (FR) Sérialisation des info de l'image pour les sauvegarder dans la base données
                (EN) Serialization of image info to save the database */
                $data = array(

                    'name' => $temp['name'],
                    'url' =>  $temp_date[0] . '/' . $temp_date[1] . '/' . $temp['name'],
                    'type' => 'img',
           
                );

                /* (FR)Injection de $data dans request */
                $this->request->data = $data;
                $this->Session->setFlash('Images ajouté avec succès à la galerie');

            } else {
                /* (FR)Si il a déjà un fichier du même nom on redirige avec un message d'erreur */
                $this->Session->setFlash('Un fichier portant ce nom est cette extension existe déjà');
                $this->redirect('admin/medias/galerie/');
            }
        }
        /* (FR)On sauvegarde les info dans la base de donnée */
        $this->Media->save($this->request->data);
        /* (FR) Et on redirige vers la galerie */
        $this->redirect('admin/medias/galerie/');
    }

    function admin_upload()
    {
        $this->loadModel('Media');

        $this->theme = 'clear';

        reset($_FILES);

        /* (FR) Je récupère le premier élément et je stocke dans la variable $temps */
        /* (EN) I get the first élément and I store in the variable $temps */
        $temp = current($_FILES);


        /* (FR) Je vérifie que le fichier a été transmis par le HTTP POST */
        /* (EN) I verify that the file was transmitted by HTTP POST*/
        if (is_uploaded_file($temp['tmp_name'])) {

            /* (FR)Je vais vérifier que les extensions correspondent */
            /* (EN)I will check that the extensions match */
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            /* (FR)Récupération de la date courante
            (FN)Retrieving the current date */
            $date = (date('Y,m'));
            $temp_date = explode(',', $date);
            $date = $temp_date[0] . DS . $temp_date[1];
            $dir = WEBROOTT . DS . 'img' . DS . $date;
       
            /*(FR)Vérifie si le dossier existe
             (EN)Check if the folder exists */
            if (!file_exists($dir)) {

                mkdir($dir, 0777, true);
            }
            /*(FR) Je définis le chemin où je vais enregistrer mon image et je la stock dans la variable $filetowrite*/
            /* (EN)I define the path where I will save my image and I store it in the variable $filetowrite */
            $filetowrite = $dir .  DS . $temp['name'];

            /*(FR)Vérifie si le fichier existe
            (EN) Check if the file exists */
            if (!file_exists($dir . DS . $temp['name'])) {

                /* (FR)Je déplacer fichier dans le dossier image
                (EN) I move file to image folder */
                move_uploaded_file($temp['tmp_name'], $filetowrite);

                /* (FR) Sérialisation des info de l'image pour les sauvegarder dans la base données
                (EN) Serialization of image info to save the database */
                $data = array(
                    'name' => $temp['name'],
                    'file' =>  $temp_date[0] . '/' . $temp_date[1] . '/' . $temp['name'],
                    'type' => 'img',
                    'info' => ''
                );

                $this->Media->save($data);
            }

            $filetowrite = WEBROOTT . '/' . 'img' . '/' . $temp_date[0] . '/' . $temp_date[1] . '/' . $temp['name'];
            /*  Je crée un nouveau tableau dans laquelle je rajoute une clé et que je nomme
            location qui aura le chemin vers mon image.*/

            /* (EN)I create a new table in which I add a key and which I name
            location which will have the way to my image. */
            // $file = array('location' =>   $filetowrite);
            $d = array(
                'file' => array('location' =>   $filetowrite)
            );


            $this->set($d);
        } else {
            /*  (FR) Je retourne une erreur 
            (EN)I return an error*/
            header("HTTP/1.1 500 Server Error");
        }
    }

    function admin_galerie()
    {
        $this->loadModel('Media');
        $d['images'] = $this->Media->find(array());
        $this->set($d);
    }
}
