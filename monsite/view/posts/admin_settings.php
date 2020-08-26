<?php if (isset($reseau)) : ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 ">
            <h6 class="m-0 font-weight-bold text-primary">Réseau social settings</h6>
        </div>

        <form action="<?php echo Router::url('admin/posts/settings') ?>" method="POST">
            <div class="card-body">
                <?php foreach ($reseau as $key => $value) : ?>

                    <input type="hidden" name="id" value="<?= $value->id ?>">

                    <label for="name" style="width: 180px;"> Non du Réseau</label>
                    <label for="Api" style="width: 180px;"> Clé de l'api</label>
                    <label for="url" style="width: 180px;"> votre compte</label><br>

                    <input type="text" name="name" id="name" style="height: 35px; wiedth:180px" value="<?= $value->name ?>">
                    <input type="text" name="api_key" id="Api" style="height: 35px;" value="<?= $value->api_key ?>">
                    <input type="text" name="link" id="url" style="height: 35px;" value="<?= $value->link ?>">

                    <a href="">
                        <input type="hidden" name="img" value="<?= $value->img ?>">
                        <img src="<?= Router::webroot($value->img)  ?>" style="width: 60px; margin:0;  transform: translateY(-15px); " alt="">
                    </a><br>

                    <button type="submit" name="reseau" class="btn-danger">
                        Mettre a jour
                    </button> <br>
                    <hr>
                <?php endforeach ?>

            </div>
        </form>
    </div>
    
<?php elseif (isset($rgpd)) :  ?>

 
    <div class="card shadow mb-4">
        <div class="card-header py-3 ">
            <h6 class=" m-0 font-weight-bold text-primary">Mentions légales & RGPD settings</h6>
        </div>
        <?php foreach ($rgpd as $key => $value) : ?>
            <form action="<?php echo Router::url('admin/posts/settings') ?>" method="POST">
                <input type="hidden" name="id" value="<?= $value->id ?>">
                <div class="card-body">
                    <h3><?= $value->name ?></h3>

                    <textarea name="chart" class="inputcontent" style="width: 60%; height: 70vh ; ">
                        <?= $value->content ?>
                    </textarea>
                </div>
               
                <button type="submit" name="rgpd" class="btn-danger">
                    Mettre a jour
                </button>
            </form>
            <hr>
        <?php endforeach ?>
    </div>




<?php elseif (isset($email)) :  ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 ">


            <h6 class=" m-0 font-weight-bold text-primary">Email Edition</h6>

        </div>
        <?php foreach ($email as $key => $value) : ?>
            <form action="<?php echo Router::url('admin/posts/settings') ?>" method="POST">


                <input type="hidden" name="id" value="<?= $value->id ?>">
                <input type="hidden" name="name" value="<?= $value->name ?>">

                <div class="card-body">

                    <?php if ($value->name == 'register') : ?>

                        <h3>Email de confirmation d'inscription</h3>
                    <?php elseif ($value->name == 'contact') : ?>
                        <h3>Email de confirmation du formulaire de contact</h3>
                    <?php elseif ($value->name == 'news') : ?>
                        <h3>Email de confirmation d'inscription newsletter</h3>
                    <?php endif ?>
                    <textarea name="email" class="inputcontent" style="width: 60%; height: 70vh ; ">
                <?= $value->email ?>
            </textarea>


                </div>
                <hr>


                <button type="submit" name="message" class="btn-danger ">
                    Mise à jour
                </button>
            </form>
        <?php endforeach ?>
        <br>


    </div>
<?php endif ?>