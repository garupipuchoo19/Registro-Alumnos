<?php
session_start();
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header('Location: alumnos_registrados.php');
    exit();
}

$id = $_GET['id'];

// Obtener datos del alumno
$query_alumno = "SELECT * FROM alumnos WHERE id_alumno = $id";
$result_alumno = $conexion->query($query_alumno);
$alumno = $result_alumno->fetch_assoc();

if (!$alumno) {
    header('Location: alumnos_registrados.php');
    exit();
}

// Obtener grupos activos
$query_grupos = "SELECT g.id_grupo, g.clave, c.nombre as carrera 
                 FROM grupos g 
                 JOIN carreras c ON g.id_carrera = c.id_carrera 
                 WHERE g.activo = 1 
                 ORDER BY g.clave";
$result_grupos = $conexion->query($query_grupos);

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido_paterno = $conexion->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = $conexion->real_escape_string($_POST['apellido_materno']);
    $id_grupo = $_POST['id_grupo'];
    
    $query = "UPDATE alumnos SET 
              nombre = '$nombre',
              apellido_paterno = '$apellido_paterno',
              apellido_materno = '$apellido_materno',
              id_grupo = $id_grupo
              WHERE id_alumno = $id";
    
    if ($conexion->query($query)) {
        header('Location: alumnos_registrados.php?mensaje=Alumno actualizado');
        exit();
    } else {
        $mensaje = "Error al actualizar alumno: " . $conexion->error;
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <!-- Estilos iguales a registro_alumnos.php -->
    <style>
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

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: #3498db;
            outline: none;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background-color: white;
        }

        .btn-submit {
            background: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #2980b9;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            
            .form-container {
                padding: 20px;
            }
            
            .btn-cancel {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
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
                    <a href="configuracion.php" class="nav-link">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="header">
                <h2><i class="fas fa-user-edit"></i> Editar Alumno</h2>
            </div>

            <div class="form-container">
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-error">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <h3 class="form-title">Editar Datos del Alumno</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="nombre" class="form-input" 
                               value="<?php echo htmlspecialchars($alumno['nombre']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Apellido Paterno:</label>
                        <input type="text" name="apellido_paterno" class="form-input" 
                               value="<?php echo htmlspecialchars($alumno['apellido_paterno']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Apellido Materno:</label>
                        <input type="text" name="apellido_materno" class="form-input" 
                               value="<?php echo htmlspecialchars($alumno['apellido_materno'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Grupo:</label>
                        <select name="id_grupo" class="form-select" required>
                            <option value="">Seleccionar Grupo</option>
                            <?php while ($grupo = $result_grupos->fetch_assoc()): ?>
                                <option value="<?php echo $grupo['id_grupo']; ?>"
                                    <?php echo ($alumno['id_grupo'] == $grupo['id_grupo']) ? 'selected' : ''; ?>>
                                    <?php echo $grupo['clave'] . ' - ' . $grupo['carrera']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Actualizar Alumno
                    </button>
                    
                    <a href="alumnos_registrados.php" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </form>
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