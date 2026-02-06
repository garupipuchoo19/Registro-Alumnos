<?php
session_start();
require_once 'conexion.php';

// Procesar acciones CRUD
if (isset($_GET['accion'])) {
    $id = $_GET['id'];
    
    switch ($_GET['accion']) {
        case 'eliminar':
            $query = "UPDATE alumnos SET activo = 0 WHERE id_alumno = $id";
            $conexion->query($query);
            break;
            
        case 'activar':
            $query = "UPDATE alumnos SET activo = 1 WHERE id_alumno = $id";
            $conexion->query($query);
            break;
    }
}

// Obtener alumnos
$query = "SELECT a.*, g.clave as grupo_clave, 
          CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', 
          IFNULL(a.apellido_materno, '')) as nombre_completo
          FROM alumnos a
          JOIN grupos g ON a.id_grupo = g.id_grupo
          ORDER BY a.id_alumno DESC";
$result = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos Registrados</title>
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

        /* Estilos específicos para la tabla */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .table-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #3498db;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .activo {
            background: #d4edda !important;
        }

        .inactivo {
            background: #f8d7da !important;
            color: #721c24;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            transition: opacity 0.3s;
        }

        .btn-action:hover {
            opacity: 0.8;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-activate {
            background: #27ae60;
            color: white;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .status-inactive {
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
            
            table {
                font-size: 14px;
            }
            
            .btn-action {
                padding: 4px 8px;
                font-size: 12px;
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
                    <a href="alumnos_registrados.php" class="nav-link active">
                        <i class="fas fa-list"></i> Alumnos Registrados
                    </a>
                </li>
                <li class="nav-item">
                    <a href="configuracion.php" class="nav-link">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="header">
                <h2><i class="fas fa-list"></i> Alumnos Registrados</h2>
            </div>

            <div class="table-container">
                <h3 class="table-title">Lista de Alumnos</h3>
                
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
                        <?php while ($alumno = $result->fetch_assoc()): ?>
                            <tr class="<?php echo $alumno['activo'] ? 'activo' : 'inactivo'; ?>">
                                <td><?php echo $alumno['id_alumno']; ?></td>
                                <td><?php echo $alumno['nombre_completo']; ?></td>
                                <td><?php echo $alumno['grupo_clave']; ?></td>
                                <td>
                                    <?php if ($alumno['activo']): ?>
                                        <span class="status-active">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="status-inactive">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="editar_alumno.php?id=<?php echo $alumno['id_alumno']; ?>" 
                                       class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    <?php if ($alumno['activo']): ?>
                                        <a href="?accion=eliminar&id=<?php echo $alumno['id_alumno']; ?>" 
                                           class="btn-action btn-delete"
                                           onclick="return confirm('¿Está seguro de eliminar este alumno?')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a>
                                    <?php else: ?>
                                        <a href="?accion=activar&id=<?php echo $alumno['id_alumno']; ?>" 
                                           class="btn-action btn-activate">
                                            <i class="fas fa-check"></i> Activar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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