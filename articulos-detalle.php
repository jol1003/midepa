<?
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $id = testins($_GET["id"]); if(!is_numeric($id)){header("location:".URL_PATH);}
  
  $inm = $db->fetchObject("SELECT id,titulo,urltitulo,sumilla,cuerpo,imagen FROM articulo WHERE estado='1' AND id=".$id." LIMIT 0,1");
  if($inm->id==""){
	header("location:".URL_PATH);
  }else{
  	$ntit = testsho($inm->titulo);
	$ntim = str_replace('"','\'',$ntit);
  	$nsml = testsho($inm->sumilla);
	$ncrp = $inm->cuerpo;
  	$nlnk = URL_PATH.'articulo-detalle/'.$inm->id.'/'.$inm->urltitulo;
  	$nimg = '<img src="'.URW_PATH.'imagenes/articulos/'.$inm->imagen.'" alt="'.$ntim.'" />';
  }
  
  $page = "articulos";
  $title = $ntim;
  include(DIR_PATH."assets/includes/header.php");?>
<div class="izq">
  <div class="mad"><h1><?=$ntit;?></h1></div>
  <div class="mad"><?=$nsml;?></div>
  <div class="fcen mod"><?=$nimg;?></div>
  <div class="mod"><?=$ncrp;?></div>
  <div class="fb-comments its" data-href="<?=$nlnk;?>" data-num-posts="10" data-width="504"></div>
</div>
<div class="der">
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img1.jpg" /></div>
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img4.jpg" /></div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>