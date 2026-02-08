<?php
session_start();
require_once 'conexion.php';

/* Obtener grupos activos */
$query_grupos = "SELECT g.id_grupo, g.clave, c.nombre as carrera 
                 FROM grupos g 
                 JOIN carreras c ON g.id_carrera = c.id_carrera 
                 WHERE g.activo = 1 
                 ORDER BY g.clave";
$result_grupos = $conexion->query($query_grupos);

/* Procesar formulario */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conexion->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = $conexion->real_escape_string($_POST['apellido_materno']);
    $id_grupo = $_POST['id_grupo'];

    $query = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, id_grupo) 
              VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', $id_grupo)";

    if ($conexion->query($query)) {
        $mensaje = "Alumno registrado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al registrar alumno: " . $conexion->error;
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Alumnos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ===== MISMO CSS DEL INDEX ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

body {
    background-color: #f1f8f4;
    color: #2f4f3f;
}

.topbar {
    background: linear-gradient(90deg, #1e8449, #27ae60);
    color: white;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.topbar .logo {
    font-size: 22px;
    font-weight: bold;
}

.topbar .logo i {
    margin-right: 8px;
}

.menu {
    display: flex;
    gap: 20px;
}

.menu a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 6px;
}

.menu a:hover,
.menu a.active {
    background: rgba(255,255,255,0.2);
}

.main-content {
    padding: 30px;
}

.card {
    background: white;
    padding: 35px;
    border-radius: 10px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card h3 {
    margin-bottom: 20px;
    color: #1e8449;
}

.form-group {
    margin-bottom: 18px;
}

label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

input, select {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #cce5d6;
}

button {
    background: #27ae60;
    color: white;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    cursor: pointer;
}

.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
}
</style>
</head>

<body>

<!-- ===== TOPBAR ===== -->
<nav class="topbar">
    <div class="logo">
        <i class="fas fa-school"></i> Sistema Escolar
    </div>

    <div class="menu">
        <a href="index.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="registro_alumnos.php" class="active"><i class="fas fa-user-plus"></i> Alumnos</a>
        <a href="registro_grupos.php"><i class="fas fa-users"></i> Grupos</a>
        <a href="alumnos_registrados.php"><i class="fas fa-list"></i> Listado</a>
        <a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a>
    </div>
</nav>

<!-- ===== CONTENIDO ===== -->
<main class="main-content">

    <div class="card">
        <h3><i class="fas fa-user-plus"></i> Registro de Alumnos</h3>

        <?php if(isset($mensaje)): ?>
            <div class="alert <?= $tipo_mensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" required>
            </div>

            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno">
            </div>

            <div class="form-group">
                <label>Grupo</label>
                <select name="id_grupo" required>
                    <option value="">Seleccionar grupo</option>
                    <?php while($g = $result_grupos->fetch_assoc()): ?>
                        <option value="<?= $g['id_grupo'] ?>">
                            <?= $g['clave'] ?> - <?= $g['carrera'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button>
                <i class="fas fa-save"></i> Registrar Alumno
            </button>
        </form>
    </div>

</main>

<script>
const current = location.pathname.split('/').pop();
document.querySelectorAll('.menu a').forEach(link => {
    if (link.getAttribute('href') === current) {
        link.classList.add('active');
    }
});
</script>

</body>
</html>
