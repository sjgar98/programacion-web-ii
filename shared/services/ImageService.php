<?php

class ImageService
{
    function procesar_imagen($archivo) {

        $nombre_final = 'default.webp';
        $errores = [];

        if (isset($archivo) && $archivo['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $archivo['tmp_name'];
            $fileName    = $archivo['name'];
            $fileSize    = $archivo['size'];
            $fileType    = $archivo['type'];
            $extensiones_permitidas = ['image/jpeg', 'image/png', 'image/webp'];

            $max_size = 2 * 1024 * 1024;

            if (!in_array($fileType, $extensiones_permitidas)) {
                $errores[] = "Formato no permitido.";
            }

            if ($fileSize > $max_size) {

                $errores[] = "Archivo muy pesado.";

            }

            if (empty($errores)) {

                $carpeta_destino = 'public/avatars/';

                if (!is_dir($carpeta_destino)) {

                    mkdir($carpeta_destino, 0755, true);

                }

                $nombre_limpio = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
                $nombre_final = time() . "_" . $nombre_limpio;
                $destino_completo = $carpeta_destino . $nombre_final;

                if (!move_uploaded_file($fileTmpPath, $destino_completo)) {
                    $nombre_final = 'default.webp';
                }

            } else{
                Log::info("Errores en la subida del avatar, se asignará la imagen por defecto.");
            }

        }
        return $nombre_final;
    }
}
