
  <?php
  $conexion = mysqli_connect("localhost", "root", "", "usuarios");
  if (!$conexion) {
    die("Problemas con la conexión: " . mysqli_connect_error());
}
 /* mysqli_query($conexion, "insert into usuarios(Usuario_nombre,Usuario_apellido1,Usuario_clave,Usuario_email) values 
                       ('$_POST[nombre]','$_POST[apellidos]','$_POST[contraseña]','$_POST[correo]')")
    or die("Problemas en el select" . mysqli_error($conexion));
    mysqli_close($conexion);

  echo "El cliente fue dado de alta.";
    */
    $mensaje = "Inicialización...";  
    $redirigir = false;
    
    if (isset($_POST['usuario'],$_POST['apellidos'], $_POST['correo'], $_POST['password'])) {
        $mensaje = "Datos del formulario recibidos..."; 
    
         // Verificar que la contraseña no es la misma
         if (isset($_POST['usuario'],$_POST['apellidos'], $_POST['correo'], $_POST['password'], $_POST['password2'])) {
            $mensaje = "Datos del formulario recibidos...";
        
            // Verificación de coincidencia de contraseñas
            if ($_POST['password'] !== $_POST['password2']) {
                echo $mensaje = "La contraseña no coincide.";
                
                echo "<script>
                setTimeout(function() {
                    
                    location.href = 'http://localhost/Formulario%20de%20usuario/Registro.html';
                }, 1000);  // Redirige después de 3000 ms (3 segundos).
            </script>";
            die;
            }
    
        }
        
        // Verificar si el correo ya está registrado
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE Usuario_email = ?");
        $stmt->bind_param("s", $_POST['correo']);
        
        if (!$stmt->execute()) {
            die("Error al ejecutar consulta: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $mensaje = "El correo ya está en uso.";
        } else {
            $contraseñaHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (Usuario_nombre,Usuario_apellido1, Usuario_email, Usuario_clave) VALUES (?,?, ?, ?)");
            
            if (!$stmt) {
                die("Error en preparación: " . $conexion->error);
            }
            
            $stmt->bind_param("ssss", $_POST['usuario'],$_POST['apellidos'], $_POST['correo'], $contraseñaHash);
    
            if ($stmt->execute()) {
                $mensaje = "El cliente fue dado de alta.";
                $redirigir = true;
            } else {
                $mensaje = "Error al registrar: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $mensaje = "Datos del formulario no recibidos.";
    }
    
    mysqli_close($conexion);
    
    echo $mensaje;
    
    // Si se necesita redirigir, añade el script de redirección.
    if ($redirigir= true ) {
        echo "<script>
            setTimeout(function() {
                location.href = 'http://localhost/Formulario%20de%20usuario/menu.html';
            }, 3000);  // Redirige después de 3000 ms (3 segundos).
        </script>";
    }
    
    ?>