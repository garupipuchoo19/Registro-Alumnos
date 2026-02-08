<?php
session_start();
require_once 'conexion.php';

// Procesar acciones CRUD
if (isset($_GET['accion'])) {
    $id = $_GET['id'];

    if ($_GET['accion'] === 'eliminar') {
        $conexion->query("UPDATE alumnos SET activo = 0 WHERE id_alumno = $id");
    }

    if ($_GET['accion'] === 'activar') {
        $conexion->query("UPDATE alumnos SET activo = 1 WHERE id_alumno = $id");
    }
}

// Obtener alumnos
$query = "SELECT a.*, g.clave AS grupo_clave,
          CONCAT(a.nombre,' ',a.apellido_paterno,' ',IFNULL(a.apellido_materno,'')) AS nombre_completo
          FROM alumnos a
          JOIN grupos g ON a.id_grupo = g.id_grupo
          ORDER BY a.id_alumno ASC";
$result = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Alumnos Registrados</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,sans-serif;
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

.topbar .logo{
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
}

.card{
    background:white;
    border-radius:10px;
    padding:30px;
    box-shadow:0 2px 4px rgba(0,0,0,.1);
}

.card h3{
    margin-bottom:20px;
    color:#1e8449;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#27ae60;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr.activo{
    background:#ecf9f1;
}

tr.inactivo{
    background:#f8d7da;
}

.status-active{
    color:#1e8449;
    font-weight:bold;
}

.status-inactive{
    color:#c0392b;
    font-weight:bold;
}

.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    color:white;
    font-size:14px;
}

.btn-edit{ background:#f39c12; }
.btn-delete{ background:#e74c3c; }
.btn-activate{ background:#27ae60; }
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
        <a href="registro_grupos.php"><i class="fas fa-users"></i> Grupos</a>
        <a href="alumnos_registrados.php" class="active"><i class="fas fa-list"></i> Listado</a>
        <a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a>
    </div>
</nav>

<!-- ===== CONTENIDO ===== -->
<main class="main-content">

<div class="card">
    <h3><i class="fas fa-list"></i> Alumnos Registrados</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Grupo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($a = $result->fetch_assoc()): ?>
            <tr class="<?= $a['activo'] ? 'activo' : 'inactivo' ?>">
                <td><?= $a['id_alumno'] ?></td>
                <td><?= $a['nombre_completo'] ?></td>
                <td><?= $a['grupo_clave'] ?></td>
                <td>
                    <?= $a['activo']
                        ? '<span class="status-active"><i class="fas fa-check-circle"></i> Activo</span>'
                        : '<span class="status-inactive"><i class="fas fa-times-circle"></i> Inactivo</span>' ?>
                </td>
                <td>
                    <a href="editar_alumno.php?id=<?= $a['id_alumno'] ?>" class="btn btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <?php if($a['activo']): ?>
                        <a href="?accion=eliminar&id=<?= $a['id_alumno'] ?>" 
                           class="btn btn-delete"
                           onclick="return confirm('¿Desactivar alumno?')">
                           <i class="fas fa-trash"></i>
                        </a>
                    <?php else: ?>
                        <a href="?accion=activar&id=<?= $a['id_alumno'] ?>" 
                           class="btn btn-activate">
                           <i class="fas fa-check"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</main>

<script>
const current = location.pathname.split('/').pop();
document.querySelectorAll('.menu a').forEach(link=>{
    if(link.getAttribute('href')===current){
        link.classList.add('active');
    }
});
</script>

</body>
</html>
