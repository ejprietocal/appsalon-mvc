<h1 class="nombre-pagina">Nuevo todos los campos para añadir un nuevo servicio</h1>


<?php include_once __DIR__ . '/../templates/barra.php'?>
<?php include_once __DIR__ . '/../templates/alertas.php'?>


<form class="formulario" action="/servicios/crear" method="POST">

    <?php include_once __DIR__ . '/formulario.php' ;?>



    <input class="boton" type="submit" value="Guardar Servicio">
</form>