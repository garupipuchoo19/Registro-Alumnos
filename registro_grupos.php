<?php
session_start();
require_once 'conexion.php';

// Obtener datos para los select
$query_carreras = "SELECT * FROM carreras WHERE activa = 1 ORDER BY nombre";
$query_turnos   = "SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre";
$query_grados   = "SELECT * FROM grados WHERE activo = 1 ORDER BY grado";

$result_carreras = $conexion->query($query_carreras);
$result_turnos   = $conexion->query($query_turnos);
$result_grados   = $conexion->query($query_grados);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_carrera = $_POST['id_carrera'];
    $id_grado   = $_POST['id_grado'];
    $id_turno   = $_POST['id_turno'];
    $clave      = $conexion->real_escape_string($_POST['clave']);

    $query_check = "SELECT id_grupo FROM grupos WHERE clave = '$clave'";
    $result_check = $conexion->query($query_check);

    if ($result_check->num_rows > 0) {
        $mensaje = "Error: La clave de grupo ya existe";
        $tipo_mensaje = "error";
    } else {
        $query = "INSERT INTO grupos (clave, id_carrera, id_grado, id_turno) 
                  VALUES ('$clave', $id_carrera, $id_grado, $id_turno)";

        if ($conexion->query($query)) {
            $mensaje = "Grupo registrado exitosamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar grupo: " . $conexion->error;
            $tipo_mensaje = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Grupos</title>
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
    max-width: 800px;
    margin: auto;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card h3 {
    margin-bottom: 20px;
    color: #1e8449;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
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
        <a href="registro_alumnos.php"><i class="fas fa-user-plus"></i> Alumnos</a>
        <a href="registro_grupos.php" class="active"><i class="fas fa-users"></i> Grupos</a>
        <a href="alumnos_registrados.php"><i class="fas fa-list"></i> Listado</a>
        <a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a>
    </div>
</nav>

<!-- ===== CONTENIDO ===== -->
<main class="main-content">

<div class="card">
    <h3><i class="fas fa-users"></i> Registro de Grupos</h3>

    <?php if (isset($mensaje)): ?>
        <div class="alert <?= $tipo_mensaje ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-row">
            <div>
                <label>Carrera</label>
                <select name="id_carrera" required>
                    <option value="">Seleccionar Carrera</option>
                    <?php while ($c = $result_carreras->fetch_assoc()): ?>
                        <option value="<?= $c['id_carrera'] ?>">
                            <?= $c['nombre'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label>Grado</label>
                <select name="id_grado" required>
                    <option value="">Seleccionar Grado</option>
                    <?php while ($g = $result_grados->fetch_assoc()): ?>
                        <option value="<?= $g['id_grado'] ?>">
                            <?= $g['grado'] ?>°
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div>
                <label>Turno</label>
                <select name="id_turno" required>
                    <option value="">Seleccionar Turno</option>
                    <?php while ($t = $result_turnos->fetch_assoc()): ?>
                        <option value="<?= $t['id_turno'] ?>">
                            <?= $t['nombre'] ?> (<?= $t['abreviatura'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label>Clave del Grupo</label>
                <input type="text" name="clave" required>
            </div>
        </div>

        <button>
            <i class="fas fa-save"></i> Registrar Grupo
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
