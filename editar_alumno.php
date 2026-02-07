<?php
session_start();
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header('Location: alumnos_registrados.php');
    exit();
}

$id = $_GET['id'];

// Obtener datos del alumno
$alumno = $conexion->query("SELECT * FROM alumnos WHERE id_alumno = $id")->fetch_assoc();
if (!$alumno) {
    header('Location: alumnos_registrados.php');
    exit();
}

// Obtener grupos activos
$grupos = $conexion->query("
    SELECT g.id_grupo, g.clave, c.nombre AS carrera
    FROM grupos g
    JOIN carreras c ON g.id_carrera = c.id_carrera
    WHERE g.activo = 1
    ORDER BY g.clave
");

// Actualizar alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $ap = $conexion->real_escape_string($_POST['apellido_paterno']);
    $am = $conexion->real_escape_string($_POST['apellido_materno']);
    $id_grupo = $_POST['id_grupo'];

    $conexion->query("
        UPDATE alumnos SET
        nombre='$nombre',
        apellido_paterno='$ap',
        apellido_materno='$am',
        id_grupo=$id_grupo
        WHERE id_alumno=$id
    ");

    header('Location: alumnos_registrados.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Alumno</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, sans-serif;
}
body{
    background:#f1f8f4;
    color:#2f4f3f;
}

/* ===== TOPBAR ===== */
.topbar{
    background:linear-gradient(90deg,#1e8449,#27ae60);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}
.logo{
    font-size:22px;
    font-weight:bold;
}
.menu{
    display:flex;
    gap:20px;
}
.menu a{
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
}
.menu a.active,
.menu a:hover{
    background:rgba(255,255,255,.2);
}

/* ===== CONTENIDO ===== */
.main-content{
    padding:30px;
    max-width:700px;
    margin:auto;
}
.card{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 2px 4px rgba(0,0,0,.1);
}
.card h2{
    color:#1e8449;
    margin-bottom:20px;
}
.form-group{
    margin-bottom:18px;
}
label{
    display:block;
    margin-bottom:6px;
    font-weight:600;
}
input, select{
    width:100%;
    padding:12px;
    border:2px solid #cdeee0;
    border-radius:6px;
    font-size:15px;
}
input:focus, select:focus{
    border-color:#27ae60;
    outline:none;
}
.buttons{
    display:flex;
    gap:10px;
    margin-top:20px;
}
.btn{
    padding:12px 25px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:15px;
    color:white;
}
.btn-save{
    background:#27ae60;
}
.btn-save:hover{
    background:#1e8449;
}
.btn-cancel{
    background:#95a5a6;
    text-decoration:none;
    display:flex;
    align-items:center;
}
.btn-cancel:hover{
    background:#7f8c8d;
}
</style>
</head>

<body>

<!-- TOPBAR -->
<nav class="topbar">
    <div class="logo"><i class="fas fa-school"></i> Sistema Escolar</div>
    <div class="menu">
        <a href="index.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="registro_alumnos.php"><i class="fas fa-user-plus"></i> Alumnos</a>
        <a href="registro_grupos.php"><i class="fas fa-users"></i> Grupos</a>
        <a href="alumnos_registrados.php" class="active"><i class="fas fa-list"></i> Listado</a>
    </div>
</nav>

<!-- CONTENIDO -->
<main class="main-content">
    <div class="card">
        <h2><i class="fas fa-user-edit"></i> Editar Alumno</h2>

        <form method="POST">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" value="<?= htmlspecialchars($alumno['apellido_paterno']) ?>" required>
            </div>

            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno" value="<?= htmlspecialchars($alumno['apellido_materno']) ?>">
            </div>

            <div class="form-group">
                <label>Grupo</label>
                <select name="id_grupo" required>
                    <?php while($g=$grupos->fetch_assoc()): ?>
                        <option value="<?= $g['id_grupo'] ?>"
                            <?= $g['id_grupo']==$alumno['id_grupo']?'selected':'' ?>>
                            <?= $g['clave'].' - '.$g['carrera'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="buttons">
                <button class="btn btn-save">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="alumnos_registrados.php" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</main>

</body>
</html>
