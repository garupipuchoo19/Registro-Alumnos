<?php
session_start();
require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ICONOS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
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

        /* ===== TOPBAR ===== */
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
            transition: background 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background: rgba(255,255,255,0.2);
        }

        /* ===== CONTENIDO ===== */
        .main-content {
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .welcome {
            background: white;
            padding: 35px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: #ecf9f1;
            padding: 25px;
            border-radius: 10px;
            border-left: 6px solid #27ae60;
        }

        .stat-card h4 {
            color: #2f4f3f;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 34px;
            font-weight: bold;
            color: #1e8449;
        }

        @media (max-width: 768px) {
            .menu {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 10px;
            }

            .topbar {
                flex-direction: column;
                align-items: center;
            }
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
        <a href="index.php" class="active"><i class="fas fa-home"></i> Inicio</a>
        <a href="registro_alumnos.php"><i class="fas fa-user-plus"></i> Alumnos</a>
        <a href="registro_grupos.php"><i class="fas fa-users"></i> Grupos</a>
        <a href="alumnos_registrados.php"><i class="fas fa-list"></i> Listado</a>
        <a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a>
    </div>
</nav>

<!-- ===== CONTENIDO ===== -->
<main class="main-content">
    <div class="header">
        <h2><i class="fas fa-chart-line"></i> Panel de Control</h2>
    </div>

    <div class="welcome">
        <h3>Bienvenido al Sistema de Gestión Escolar</h3>
        <p>Control de alumnos, grupos y carreras</p>

        <div class="stats">
            <?php
            $alumnos = $conexion->query("SELECT COUNT(*) total FROM alumnos WHERE activo = 1")->fetch_assoc();
            $grupos  = $conexion->query("SELECT COUNT(*) total FROM grupos WHERE activo = 1")->fetch_assoc();
            $carreras= $conexion->query("SELECT COUNT(*) total FROM carreras WHERE activa = 1")->fetch_assoc();
            ?>

            <div class="stat-card">
                <h4>Alumnos Activos</h4>
                <div class="stat-number"><?= $alumnos['total'] ?></div>
            </div>

            <div class="stat-card">
                <h4>Grupos Activos</h4>
                <div class="stat-number"><?= $grupos['total'] ?></div>
            </div>

            <div class="stat-card">
                <h4>Carreras</h4>
                <div class="stat-number"><?= $carreras['total'] ?></div>
            </div>
        </div>
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
