<main class="contenedor seccion">
    <h1>Contacto</h1>

    <?php if($mensaje): ?>
        <p class="alerta exito"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <picture>
        <source srcset="build/img/destacada3.webp" type="image/webp">
        <source srcset="build/img/destacada3.jpg" type="image/jpeg">
        <img src="build/img/destacada3.jpg" alt="Imagen de Contacto" loading="lazy">
    </picture>
    <h2>Llene el Formulario de Contacto</h2>
    <form action="/contacto" class="formulario" method="POST">
        <fieldset>
            <legend>Información Personal</legend>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" placeholder="Tu Nombre" name="contacto[nombre]" >

            

            

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="contacto[mensaje]" ></textarea>
        </fieldset>
        <fieldset>
            <legend>Información sobre la Propiedad</legend>
            <label for="opciones">Vende o Compra</label>
            <select name="contacto[tipo]" id="opciones" >
                <option value="" disabled selected>Seleccione</option>
                <option value="Vende">Vende</option>
                <option value="Compra">Compra</option>
            </select>

            <label for="presupuesto">Precio o Presupuesto</label>
            <input type="number" id="presupuesto" placeholder="Tu Precio o Presupuesto" name="contacto[precio]" >
        </fieldset>
        <fieldset>
            <legend>Contacto</legend>
            <p>¿Cómo desea ser contactado?</p>
            <div class="forma-contacto">
                <label for="contactar-etelefono">Teléfono</label>
                <input type="radio" name="contacto[contacto]" id="contactar-etelefono" value="telefono" >

                <label for="contactar-email">Email</label>
                <input type="radio" name="contacto[contacto]" id="contactar-email" value="email" >
            </div>

            <div id="contacto"></div>

            
        </fieldset>

        <input type="submit" value="Enviar" class="boton-verde">
    </form>
</main>