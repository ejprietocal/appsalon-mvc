<div class="campo">
    <label for="nombre">Nombre del servicio</label>
    <input 
        type="text" 
        name="nombre"
        id="nombre"
        placeholder="Servicio"
        value="<?php echo $servicio->nombre ?>"
    >
</div>
<div class="campo">
    <label for="precio">Precio</label>
    <input 
        type="number" 
        name="precio"
        id="precio"
        placeholder="Precio Servicio"
        value="<?php echo $servicio->precio ?>"
    >
</div>