<?php
// Conexión a la base de datos
$host = "localhost";
$usuario_db = "root";
$nombre_db = "usuarios";
$contrasena_db = "";

$con = mysqli_connect($host, $usuario_db, $contrasena_db, $nombre_db);

if (!$con) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Recopila datos del formulario
$email = $_POST['email'];
$contraseña = $_POST['password'];
$usuarioAdministrador = "admin";

// Consulta SQL para verificar las credenciales del usuario
$sql = "SELECT * FROM usuarios WHERE Usuario_email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    // Las credenciales son válidas, el usuario ha iniciado sesión
    $fila = mysqli_fetch_assoc($result);
    $clave = $fila['Usuario_clave'];
    $perfil = $fila['Usuario_perfil'];

    if (password_verify($contraseña, $clave)) {
        if ($perfil == $usuarioAdministrador) {
            header("Location: menuAdmin.html");
        } else {
            header("Location: menu.html");
        }
        exit();
    } else {
        echo "Contraseña incorrecta";
        echo "<script>
                setTimeout(function() {
                    
                    location.href = 'http://localhost/Formulario%20de%20usuario/inicio.html';
                }, 1000);  // Redirige después de 3000 ms (3 segundos).
            </script>";
    }
} else {
    // Las credenciales son inválidas, el usuario no puede iniciar sesión
    echo "No se encuentra el email; Inténtalo de nuevo.";
    header("Location: http://localhost/Formulario%20de%20usuario/inicio.html");
    exit();
}

mysqli_close($con);
?>