

<h1 style="margin-left:600px; display:inline;">Votre Profil</h1><br>
<div style="display:block;  margin-left:500px; ">

    <form action="<?php echo Router::url('users/SaveProfilEdite') ?>" method="post" style="display:inline;" enctype="multipart/form-data" >

        <table style="margin-top: 30px;">
            <tr>
                <td>
                    <label for="login">Avatar</label>
                </td>

                <td>
                    <img src=" <?php  echo Router::webroot( $user->avatar)  ?>" alt="" width="150px">
                    <br>
                </td>

            </tr>
            <tr>
                <td>
                    <label for="login">Votre Login</label>
                </td>

                <td>
                    <input type="text" name="login" id="login" placeholder="login" value="<?php echo $user->login ?>">
                    <br>
                </td>

            </tr>
            <tr>
                <td>
                    <label for="name">Votre nom Complet</label>
                </td>
                <td>
                    <input type="text" name="name" id="name" placeholder="Votre nom" value="<?php echo $user->name ?>">
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="email">Votre Email</label>
                </td>
                <td>

                    <input type="email" name="email" id="email" placeholder="Votre Email" value="<?php echo $user->email ?>">
                    <br>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="newmp">Nouveau mot de passe</label>
                </td>
                <td>
                    <input type="password" name="newmp" id="newmp" placeholder="Mot de passe">
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="newmp2">Confirmation du mot de passe</label>
                </td>
                <td>
                    <input type="password" name="newmp2" id="newmp2" placeholder="Confirm mot de passe">
                    <br>
                </td>

            </tr>
            <tr>
                <td>
                <label for="avatar">Telecharger un Avatar</label>
                </td>
                <td>
                    <input type="file" name="avatar" id="avatar">
                </td>
            </tr>
            <tr>
                <td>
                    <h3>Votre Grade</h3>
                </td>
                <td>
                    <p><?php echo $user->role ?></p>
                </td>
            </tr>
    
        </table>
        <button type="submit">Valider</button>
    </form>

</div>