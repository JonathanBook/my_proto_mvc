 <!-- DataTales Example -->
 <!-- Topbar Search -->
 <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="<?php echo Router::url('admin/users/listeutilisateurs'); ?>" method="POST">
     <div class="input-group">
         <input type="text" class="form-control bg-light border-0 small  " name="cherche" style="background-color: #fff!important;" placeholder="chercher utilisateur" aria-label="Search" aria-describedby="basic-addon2">
         <div class="input-group-append">
             <button class="btn btn-primary" type="SUBMIT">
                 <i class="fas fa-search fa-sm"></i>
             </button>
         </div>
     </div>
 </form>
 <br>
 <hr>
 <br>

 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Liste Utilisateurs</h6>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                     <tr>
                         <th>Login</th>
                         <th>Name</th>
                         <th>Date d'inscription</th>
                         <th>Date de début de la formation</th>
                         <th>Rôle de l'utilisateur</th>
                     </tr>
                 </thead>
                 <tfoot>
                     <tr>
                         <th>Login</th>
                         <th>Name</th>
                         <th>Date d'inscription</th>
                         <th>Date de début de la formation</th>
                         <th>Rôle de l'utilisateur</th>
                     </tr>
                 </tfoot>
                 <tbody>
                     <?php foreach ($Users as $key => $value) : ?>
                         <tr>
                             <td><?= $value->login ?></td>
                             <td><?= $value->name ?></td>
                             <td><?= $value->date_register ?></td>
                             <?php if (empty($value->start_training_date)) {
                                    $value->start_training_date = 'Aucune formation en cours';
                                } ?>
                             <td><?= $value->start_training_date ?></td>
                             <td><?= $value->role ?></td>
                             <td>
                                 <a href="#" class="btn btn-secondary btn-icon-split">
                                     <span class="icon text-white-50">
                                         <i class="fas fa-arrow-right"></i>
                                     </span>
                                     <span class="text">Voir Profil Utilisateur</span>
                                 </a>
                             </td>


                         </tr>
                     <?php endforeach ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>