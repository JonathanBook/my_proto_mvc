<div>
    <form action="<?php echo Router::url('admin/medias/save') ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name='file' accept="image/png, image/jpeg , image/gif"  >  
        <div class="actions">
            <input type="submit" class="btn btn-primary" value="Ajouter une image à la galerie">
        </div>

    </form>
</div>
<hr class="sidebar-divider my-3">
<div class="card shadow mb-4">
    <div class="card-header py-3 ">
        <h6 class="m-0 font-weight-bold text-primary">Articles</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table width="100%" class="table table-bordered" id="dataTable" cellspacing="0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Image</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($images as $k => $v) : ?>
                        <form action="<?php echo Router::url('admin/medias/save/id:' . $v->id); ?> " method="POST">
                            <tr>
                                <input type="hidden" name="id" value="<?php echo $v->id ?>">
                                <td><input name="name" type="text" class='form-control' value="<?php echo $v->name; ?>"></td>
                                <td>
                                    <a href="#" onclick="insert('<?php echo Router::webroot('img/' . $v->url); ?>')">
                                        <img class="img" src="<?php echo Router::webroot('img/' . $v->url); ?>" height="100" alt="">
                                    </a>
                                </td>
                                <td>
                                    <textarea name="info" id="input" cols="10" rows="4" class='form-control'><?php echo $v->info; ?></textarea>

                                <td>
                                    <!-- Submit button -->
                                    <div class="actions">
                                        <input type="submit" class="btn btn-primary" value="Sauvegarder les modifications">
                                    </div>
                                    <br>
                                    <a class="btn btn-danger btn-icon-split" onclick="return confirm('Voulez vous vraiment supprimer cette image')" href="<?php echo Router::url(conf::$admin_prefixe.'/medias/delete/id:' . $v->id); ?>">
                                        <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                                        <span class="text">Spupprimer l'image</span>
                                    </a>

                                </td>
                            </tr>
                        </form>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</div>