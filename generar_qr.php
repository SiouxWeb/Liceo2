<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de QR</title>
    <link rel="stylesheet" href="stylesPHP.css"> <!-- Enlace al archivo CSS externo -->
</head>
<body>
    <?php
    // Verificar si la URL ha sido enviada
    if(isset($_POST['url'])){
        $url = $_POST['url'];
        
        // Definir la ruta de la imagen del marco
        $marco = 'img/escaneame.png';
        
        // Definir la ruta donde se guardará el código QR combinado con el marco
        $archivo = 'qr_codes/codigo_qr_con_marco.png';
        
        // Definir las dimensiones del marco
        $marco_width = 300; // Ancho del marco en píxeles
        $marco_height = 420; // Alto del marco en píxeles
        
        // Incluir la librería QRCode
        require 'phpqrcode/qrlib.php';
        
        // Generar el código QR
        QRcode::png($url, 'temp_qr.png', 'L', 5, 2);
        
        // Abrir tanto el código QR como el marco
        $qr = imagecreatefrompng('temp_qr.png');
        $marco_img = imagecreatefrompng($marco);
        
        // Obtener las dimensiones del código QR
        $qr_width = imagesx($qr);
        $qr_height = imagesy($qr);
        
        // Crear una nueva imagen combinada con las dimensiones del marco
        $combined = imagecreatetruecolor($marco_width, $marco_height);
        
        // Copiar el marco sobre la imagen combinada
        imagecopyresampled($combined, $marco_img, 0, 0, 0, 0, $marco_width, $marco_height, imagesx($marco_img), imagesy($marco_img));
        
        // Calcular la posición para centrar el código QR dentro del marco
        $qr_x = ($marco_width - $qr_width) / 2; // Mismo valor para la posición X
        $qr_y = ($marco_height - $qr_height) / 2 - 55; // Restamos 20 píxeles para mover el código QR hacia arriba
        
        // Copiar el código QR en la imagen combinada centrado dentro del marco
        imagecopy($combined, $qr, $qr_x, $qr_y, 0, 0, $qr_width, $qr_height);
        
        // Guardar la imagen combinada
        imagepng($combined, $archivo);
        
        // Liberar la memoria
        imagedestroy($qr);
        imagedestroy($marco_img);
        imagedestroy($combined);
        
        // Mostrar la imagen combinada
        echo '<div class="container">';
        echo '<img src="'.$archivo.'" class="qr" alt="Código QR">';
        echo '<h2 class="mensaje">Listo, tu código QR con marco!</h2>';
        
        // Agregar botón de descarga
        echo '<a href="'.$archivo.'" download="codigo_QR.png"><button class="show">Descargar QR</button></a>';
        echo '</div>';
        
    } else {
        // Mostrar un mensaje de error si la URL no ha sido enviada
        echo 'Por favor, introduce una URL.';
    }
    ?>
</body>
</html>


