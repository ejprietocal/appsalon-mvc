<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu pasword escriviendo tu email a continuacion</p>


<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/olvide" method="POST" class="formulario">

    <div class="campo">
        <label for="email">E-Mail</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu E-Mail"
        >
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>