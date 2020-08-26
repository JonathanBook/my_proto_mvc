<?php
class ApiController extends Controller{
   
    function user(){
        debug($this->request->params);
        header('Content-Type: application/json');
        $this->loadModel('User');
  
        if (isset($this->request->data) && !empty($this->request->data)) {

            $data = $this->request->data;
            $data->cherche = htmlspecialchars($data->cherche);
            $cherche = $this->User->connectQuery('SELECT id, name, avatar, email, date_register, start_training_date, role FROM users WHERE name LIKE "%' . $data->cherche . '%" ORDER BY id DESC');

            if (empty($cherche)) {

                $cherche = $this->User->connectQuery('SELECT id, name, avatar, email, date_register, start_training_date, role FROM users WHERE CONCAT(name, email,login) LIKE "%' . $data->cherche . '%"  ORDER BY id DESC');
            }

            $d['Users'] = $cherche;
        } else { /* Si aucune recherche est demander on envoit la liste de tout les Utilisateur */

            $d['Users'] = $this->User->find(array(
                'fields'=>'id,name,avatar,date_register,start_training_date,role,email'
            ));
        }


        retour_json(true,'recuperation terminer', $d['Users']);

    
    }


}