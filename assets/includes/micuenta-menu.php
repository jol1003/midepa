<? if($log == ""){
header("location:".URL_PATH);
}
?>
<div class="mod">
<div class="icg flef">Mi cuenta</div>
<div class="btn frig"><span><a href="<?=URL_PATH;?>micuenta-crearpublicacion/">Registrar inmueble<? if($ltip==2){echo " o proyecto";}?></a></span></div>
<div class="flot"></div></div>

<div class="izn">
<div class="bkm mcu">
<div class="bkmi">
<ul>
<li>
<a href="<?=URL_PATH;?>micuenta/"<? if($page=="micuenta"){echo ' class="sel"';}?>>Mi perfil</a>
</li>
<li><a href="<?=URL_PATH;?>micuenta-mispublicaciones/"<? if($page=="micuenta-mispublicaciones"){echo ' class="sel"';}?>>Mis publicaciones</a><? if($ltip == 2){?>
<ul>
<li><a href="<?=URL_PATH;?>micuenta-inmuebles/">Inmuebles</a></li>
<li><a href="<?=URL_PATH;?>micuenta-proyectos/">Proyectos</a></li>
</ul><? }?></li>
<li><a href="<?=URL_PATH;?>micuenta-contrasena/"<? if($page=="micuenta-contrasena"){echo ' class="sel"';}?>>Cambiar contrase&ntilde;a</a></li>

<li><a href="<?=URL_PATH;?>pago-publicaciones.php"<? if($page=="pago-publicaciones.php"){echo ' class="sel"';}?>>Ver mi carrito</a></li>

</ul>
</div>
</div>
</div>