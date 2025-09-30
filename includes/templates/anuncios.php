<?php
    use App\Propiedad;

    if($_SERVER['SCRIPT_NAME'] === '/anuncios.php') {
        $propiedades = Propiedad::all(); //Llamamos al método estático all para obtener todas las propiedades
    }else{
        $propiedades = Propiedad::get(3); //Llamamos al método estático get para obtener un número determinado de propiedades
    }
?>

<div class="contenedor-anuncios">
    <?php foreach($propiedades as $propiedad) : ?>
    <div class="anuncio">

            <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Anuncio 3" loading="lazy">

        <div class="contenido-anuncio">
            <h3><?php echo $propiedad->titulo; ?></h3>
            <p><?php echo $propiedad->descripcion; ?></p>
            <p class="precio"><?php echo $propiedad->precio; ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" src="build/img/icono_wc.svg" alt="Icono WC" loading="lazy">
                    <p><?php echo $propiedad->wc; ?></p>
                </li>
                <li>
                    <img class="icono" src="build/img/icono_estacionamiento.svg" alt="Icono Estacionamiento" loading="lazy">
                    <p><?php echo $propiedad->estacionamiento; ?></p>
                </li>
                <li>
                    <img class="icono" src="build/img/icono_dormitorio.svg" alt="Icono Dormitorio" loading="lazy">
                    <p><?php echo $propiedad->habitaciones; ?></p>
                </li>
            </ul>

            <a href="anuncio.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Ver Propiedad</a>
        </div><!--.contenido-anuncios-->
    </div><!--.anuncio-->
    <?php endforeach; ?>
</div><!--.contenedor-anuncio-->