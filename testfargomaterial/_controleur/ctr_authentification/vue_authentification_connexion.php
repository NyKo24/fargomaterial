<?php if (isset($_GET["message"]) and $_GET["message"]=="emailsend"){?>
	<div class="alert alert-success" role="alert">Un email vous a été envoyé avec votre nouveau mot de passe</div>
<?}else if (isset($_GET["message"]) and $_GET["message"]=="noaccess"){?>
	<div class="alert alert-danger" role="alert">Vous n'avez pas accès à cette zone vous avez été déconnecté</div>
<?php }?>
<form method="post" action="<?=BASE_URL?>authentification/connexion">
        <div class="form-group">
                <label for="uti_login">Login</label>
                <input type="text" name="uti_login" value="" class="form-control"/>
        </div>
        <div class="form-group">
                <label for="uti_mdp">Mot de Passe</label>
                <input type="password" name="uti_mdp" value="" class="form-control"/>
        </div>
        <div class="checkbox">
	    	<label>
	      		<input name="souvenir" value="oui" type="checkbox"> Se souvenir de moi
	    	</label>
  		</div>
        <input class="btn btn-success" type="submit" name="btSubmit" value="Connexion" />
        <a class="btn btn-primary" href="<?=BASE_URL?>authentification/mdpoublie">Mot de passe oublié ?</a>
</form>