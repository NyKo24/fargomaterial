<?php if (isset($_GET["message"]) and $_GET["message"]=="nouser"){?>
	<div class="alert alert-danger" role="alert">Aucune utilisateur trouver avec cet email</div>
<?}?>
<form method="post" action="<?=BASE_URL?>authentification/resetmdp">
        <h3>Entrez votre adresse mail</h3>
        <div class="form-group">
                <label for="uti_login">Adresse mail</label>
                <input type="email" name="uti_email" value="" class="form-control"/>
        </div>
        <input class="btn btn-success" type="submit" name="btSubmit" value="Recevoir un nouveau mot de passe" />
        <a
</form>