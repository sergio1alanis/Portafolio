<?php include("cabecera.php"); ?>
<?php include("conexion.php"); ?>
<?php

if($_POST){ 

    $nombre = $_POST["nombre"];

    //variable $fecha sirve para diferenciar archivos con el mismo nombre
    $fecha=new DateTime();

    // A la variable $imagen se le concatena la fecha para diferenciar archivos
    // iguales o con el mismo nombre
    $imagen = $fecha->getTimestamp() . "_". $_FILES["archivo"]['name'];
    $descripcion = $_POST['descripcion'];
    $objConexion = new conexion();

    //Estas lineas son para guardar la imagen en una archivo temporal
    $imagen_temporal=$_FILES["archivo"]['tmp_name'];

    //La imagen en el archivo temporal se guarda en la carpeta imagenes
    move_uploaded_file($imagen_temporal, "imagenes/".$imagen);

    $sql="INSERT INTO proyectos(nombre,imagen,descripcion) 
    VALUES ('$nombre', '$imagen', '$descripcion');";

    $objConexion->ejecutar($sql);

    //La funciion header en este caso ayuda a que cuando se referesque la pagina con f5
    //no se ejecute nuevamente la consulta. Redirecciona a la misma pagina
    header('location:portafolio.php');
}

if($_GET){    // se comienza enviando el id para seleccionar el elemento a borrar
    $id = $_GET['borrar'];
    $objConexion= new conexion();

    //para borrar la imagen de la carpeta imagenes
    $imagen=$objConexion->consultar("SELECT imagen FROM proyectos where id = $id;");

    //unlink es una funcion que permite borrar. se coloca toda la ruta de la carpeta imagenes
    unlink("imagenes/".$imagen[0]['imagen']);
    
    //en esta parte se realiza el borrado de la base de datos
    $sql= "DELETE FROM proyectos WHERE id = $id;";
    $objConexion->ejecutar($sql);  

    //Con esta linea el usuario ya no podra actualizar f5 y no se duplique el mismo query
    header('location:portafolio.php');
}

$objConexion=new conexion();
$proyectos = $objConexion->consultar("SELECT * FROM proyectos;");
//print_r($proyectos);


?>

<br/>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
            <div class="card-header">Datos del proyecto</div>
            <div class="card-body">
            <form action="portafolio.php" method="post" enctype="multipart/form-data">
                <!-- required sirve para que no se pueda enviar datos vacios -->
            Nombre del proyecto: <input required class="form-control" type="text" name="nombre"><br>
            Imagen del proyecto: <input required class="form-control" type="file" name="archivo"><br>
            Descripcion:
            <textarea required class="form-control" name="descripcion" id="" rows="5"></textarea><br>
            <input class="btn btn-success" type="submit" value="Enviar proyecto">
            </form>
            </div>
            </div>
        </div>

        <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-primary">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Imagen</th>
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($proyectos as $proyecto) { ?>
                <tr class="">
                    <td scope="row"><?php echo $proyecto['id']; ?> </td>
                    <td><?php echo $proyecto['nombre']; ?></td>
                    <td>
                        <img width="100" src="imagenes/<?php echo $proyecto['imagen']; ?>" alt="">
                        
                    
                    </td>
                    <td><?php echo $proyecto['descripcion']; ?></td>
                    <td><a class="btn btn-danger" href="?borrar=<?php echo $proyecto['id'];?>">Eliminar</a></td>
                </tr>
                <?php } ?>

            </tbody>
            </table>
        </div>
            
        </div>
        
    </div>
</div>




<?php include("pie.php"); ?>