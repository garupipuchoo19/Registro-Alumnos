<?php
session_start();
require_once 'conexion.php';

// Procesar acciones para carreras
if (isset($_POST['agregar_carrera'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre_carrera']);
    $query = "INSERT INTO carreras (nombre) VALUES ('$nombre')";
    $conexion->query($query);
}

if (isset($_GET['eliminar_carrera'])) {
    $id = $_GET['eliminar_carrera'];
    $query = "UPDATE carreras SET activa = 0 WHERE id_carrera = $id";
    $conexion->query($query);
}

if (isset($_GET['activar_carrera'])) {
    $id = $_GET['activar_carrera'];
    $query = "UPDATE carreras SET activa = 1 WHERE id_carrera = $id";
    $conexion->query($query);
}

// Obtener carreras
$query_carreras = "SELECT * FROM carreras ORDER BY activa DESC, nombre";
$result_carreras = $conexion->query($query_carreras);

// Obtener turnos
$query_turnos = "SELECT * FROM turnos ORDER BY activo DESC, nombre";
$result_turnos = $conexion->query($query_turnos);

// Obtener grados
$query_grados = "SELECT * FROM grados ORDER BY activo DESC, grado";
$result_grados = $conexion->query($query_grados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>
    <style>
        /* Estilos del index (reutilizados) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50, #34495e);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .logo {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo h1 {
            font-size: 24px;
            color: #3498db;
        }

        .nav-menu {
            margin-top: 30px;
        }

        .nav-item {
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .nav-link:hover {
            background: rgba(52, 152, 219, 0.1);
            border-left: 4px solid #3498db;
            color: #3498db;
        }

        .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            border-left: 4px solid #3498db;
            color: #3498db;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        /* Estilos específicos para configuración */
        .config-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .config-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 600;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-small {
            background: #3498db;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-small:hover {
            background: #2980b9;
        }

        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .config-table th {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: left;
        }

        .config-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .config-table tr:hover {
            background: #f9f9f9;
        }

        .btn-icon {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .activo {
            color: #27ae60;
            font-weight: bold;
        }

        .inactivo {
            color: #e74c3c;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .nav-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .nav-item {
                white-space: nowrap;
            }
            
            .config-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar (igual que index.php) -->
        <aside class="sidebar">
            <div class="logo">
                <h1><i class="fas fa-school"></i> Sistema Escolar</h1>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a href="registro_alumnos.php" class="nav-link">
                        <i class="fas fa-user-plus"></i> Registro de Alumnos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="registro_grupos.php" class="nav-link">
                        <i class="fas fa-users"></i> Registro de Grupos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="alumnos_registrados.php" class="nav-link">
                        <i class="fas fa-list"></i> Alumnos Registrados
                    </a>
                </li>
                <li class="nav-item">
                    <a href="configuracion.php" class="nav-link active">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="header">
                <h2><i class="fas fa-cog"></i> Configuración del Sistema</h2>
            </div>

            <div class="config-container">
                <!-- Sección de Carreras -->
                <div class="config-section">
                    <h3 class="section-title">Carreras</h3>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label class="form-label">Agregar Nueva Carrera:</label>
                            <input type="text" name="nombre_carrera" class="form-input" 
                                   placeholder="Nombre de la carrera" required>
                        </div>
                        <button type="submit" name="agregar_carrera" class="btn-small">
                            <i class="fas fa-plus"></i> Agregar Carrera
                        </button>
                    </form>
                    
                    <table class="config-table">
                        <thead>
                            <tr>
                                <th>Carrera</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($carrera = $result_carreras->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $carrera['nombre']; ?></td>
                                    <td>
                                        <?php if ($carrera['activa']): ?>
                                            <span class="activo">Activa</span>
                                        <?php else: ?>
                                            <span class="inactivo">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($carrera['activa']): ?>
                                            <a href="?eliminar_carrera=<?php echo $carrera['id_carrera']; ?>" 
                                               class="btn-icon btn-danger"
                                               onclick="return confirm('¿Desactivar esta carrera?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?activar_carrera=<?php echo $carrera['id_carrera']; ?>" 
                                               class="btn-icon btn-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Sección de Turnos -->
                <div class="config-section">
                    <h3 class="section-title">Turnos</h3>
                    
                    <table class="config-table">
                        <thead>
                            <tr>
                                <th>Turno</th>
                                <th>Abreviatura</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($turno = $result_turnos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $turno['nombre']; ?></td>
                                    <td><?php echo $turno['abreviatura']; ?></td>
                                    <td>
                                        <?php if ($turno['activo']): ?>
                                            <span class="activo">Activo</span>
                                        <?php else: ?>
                                            <span class="inactivo">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Sección de Grados -->
                <div class="config-section">
                    <h3 class="section-title">Grados</h3>
                    
                    <table class="config-table">
                        <thead>
                            <tr>
                                <th>Grado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($grado = $result_grados->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $grado['grado']; ?>°</td>
                                    <td>
                                        <?php if ($grado['activo']): ?>
                                            <span class="activo">Activo</span>
                                        <?php else: ?>
                                            <span class="inactivo">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Resaltar enlace activo
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href');
                if (linkPage === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>