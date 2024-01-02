<?php
  /*alerta es un arreglo asociativo y dentro de ahi es un arreglo
  indexado, solamente tiene los indices. Mientras que alerta si tiene una
  llave que se llama "error".
  Tambien tiene el key "exito"*/
    foreach($alertas as $key => $alerta):
        foreach($alerta as $mensaje):  //accedemos a cada uno de los msj de error
?>
    <div class="alerta <?php echo $key; ?> "><?php echo $mensaje ?></div>
<?php
        endforeach;
    endforeach;
?>