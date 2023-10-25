<?php
$subirArchivo = "imgusers/"; // Directorio donde se guardarán las imágenes
$maxTamanoArchivo = 20000; // 200 Kbytes
$maxTamanoTotal = 30000; // 300 Kbytes

$errores = [];

if (!file_exists($subirArchivo)) {
    mkdir($subirArchivo, 0755,true); // Crea el directorio si no existe
}

if (isset($_FILES['archivos']['name'])) {
    $totalTamano = 0;

    foreach ($_FILES['archivos']['name'] as $key => $nombre) {
        $tamano = $_FILES['archivos']['size'][$key];
        $type = $_FILES['archivos']['type'][$key];
        $tmp_nombre = $_FILES['archivos']['tmp_name'][$key];

        // Tamaño y tipo de imagen

        if ($tamano > $maxTamanoArchivo || $tamano <= 0) {
            $errores[] = "El archivo ".$nombre." excede el tamaño máximo permitido.";
        }
       
        $tiposPermitidos = ['image/jpeg', 'image/png'];

if (!in_array($type, $tiposPermitidos)) {
            $errores[] = "El archivo ".$nombre." no es un formato de imagen válido (solo se permiten JPG y PNG).";
        }

        // Hay que poner una barra para que se distinga el directorio

        $destino = $subirArchivo.$nombre;
        if (file_exists($destino)) {
            $errores[] = "El archivo ".$nombre." ya existe en el directorio de imágenes.";
        }

        $totalTamano += $tamano;
    }

    if ($totalTamano > $maxTamanoTotal) {
        $errores[] = "El tamaño total de los archivos supera el límite permitido.";
    }

    if (empty($errores)) {
        foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_nombre) {
            $destino = $subirArchivo . $_FILES['archivos']['name'][$key];
            move_uploaded_file($tmp_nombre, $destino);
        }
        echo "Se subieron los archivos correctamente.";
    } else {
        foreach ($errores as $error) {
            echo $error . "<br>";
        }
    }
}
?>
