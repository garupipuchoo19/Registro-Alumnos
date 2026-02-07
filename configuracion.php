<?php
session_start();
require_once 'conexion.php';

/* ===== CARRERAS ===== */
if (isset($_POST['agregar_carrera'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre_carrera']);
    $conexion->query("INSERT INTO carreras (nombre) VALUES ('$nombre')");
}
if (isset($_GET['eliminar_carrera'])) {
    $conexion->query("UPDATE carreras SET activa=0 WHERE id_carrera=".$_GET['eliminar_carrera']);
}
if (isset($_GET['activar_carrera'])) {
    $conexion->query("UPDATE carreras SET activa=1 WHERE id_carrera=".$_GET['activar_carrera']);
}

$carreras = $conexion->query("SELECT * FROM carreras ORDER BY activa DESC, nombre");
$turnos   = $conexion->query("SELECT * FROM turnos ORDER BY activo DESC, nombre");
$grados   = $conexion->query("SELECT * FROM grados ORDER BY activo DESC, grado");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Configuración</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,sans-serif}
body{background:#f1f8f4;color:#2f4f3f}

/* ===== TOPBAR ===== */
.topbar{
    background:linear-gradient(90deg,#1e8449,#27ae60);
    color:#fff;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,.15)
}
.logo{font-size:22px;font-weight:bold}
.menu{display:flex;gap:20px}
.menu a{
    color:#fff;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px
}
.menu a.active,
.menu a:hover{background:rgba(255,255,255,.2)}

/* ===== CONTENIDO ===== */
.main-content{padding:30px}
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:25px
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 4px rgba(0,0,0,.1)
}
.card h3{
    color:#1e8449;
    margin-bottom:15px
}

/* ===== FORM ===== */
.form-group{margin-bottom:15px}
label{font-weight:600;display:block;margin-bottom:6px}
input{
    width:100%;
    padding:10px;
    border:2px solid #cdeee0;
    border-radius:6px
}
input:focus{outline:none;border-color:#27ae60}
.btn{
    background:#27ae60;
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:6px;
    cursor:pointer
}
.btn:hover{background:#1e8449}

/* ===== TABLE ===== */
table{width:100%;border-collapse:collapse;margin-top:15px}
th{
    background:#27ae60;
    color:#fff;
    padding:10px;
    text-align:left
}
td{
    padding:10px;
    border-bottom:1px solid #eee
}
.activo{color:#27ae60;font-weight:bold}
.inactivo{color:#e74c3c;font-weight:bold}
.icon-btn{
    padding:6px 10px;
    border-radius:5px;
    color:#fff;
    text-decoration:none
}
.icon-danger{background:#e74c3c}
.icon-success{background:#27ae60}
.icon-btn:hover{opacity:.85}
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
        <a href="alumnos_registrados.php"><i class="fas fa-list"></i> Listado</a>
        <a href="configuracion.php" class="active"><i class="fas fa-cog"></i> Configuración</a>
    </div>
</nav>

<!-- CONTENIDO -->
<main class="main-content">

<div class="grid">

<!-- CARRERAS -->
<div class="card">
    <h3><i class="fas fa-graduation-cap"></i> Carreras</h3>
    <form method="POST">
        <div class="form-group">
            <label>Nueva carrera</label>
            <input type="text" name="nombre_carrera" required>
        </div>
        <button class="btn"><i class="fas fa-plus"></i> Agregar</button>
    </form>

    <table>
        <?php while($c=$carreras->fetch_assoc()): ?>
        <tr>
            <td><?= $c['nombre'] ?></td>
            <td><?= $c['activa']?'<span class="activo">Activa</span>':'<span class="inactivo">Inactiva</span>' ?></td>
            <td>
                <?php if($c['activa']): ?>
                    <a class="icon-btn icon-danger" href="?eliminar_carrera=<?= $c['id_carrera'] ?>"><i class="fas fa-times"></i></a>
                <?php else: ?>
                    <a class="icon-btn icon-success" href="?activar_carrera=<?= $c['id_carrera'] ?>"><i class="fas fa-check"></i></a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- TURNOS -->
<div class="card">
    <h3><i class="fas fa-clock"></i> Turnos</h3>
    <table>
        <?php while($t=$turnos->fetch_assoc()): ?>
        <tr>
            <td><?= $t['nombre'] ?></td>
            <td><?= $t['abreviatura'] ?></td>
            <td><?= $t['activo']?'<span class="activo">Activo</span>':'<span class="inactivo">Inactivo</span>' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- GRADOS -->
<div class="card">
    <h3><i class="fas fa-layer-group"></i> Grados</h3>
    <table>
        <?php while($g=$grados->fetch_assoc()): ?>
        <tr>
            <td><?= $g['grado'] ?>°</td>
            <td><?= $g['activo']?'<span class="activo">Activo</span>':'<span class="inactivo">Inactivo</span>' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</div>
</main>

</body>
</html>
