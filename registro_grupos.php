<?php
session_start();
require_once 'conexion.php';

// Obtener datos para los select
$query_carreras = "SELECT * FROM carreras WHERE activa = 1 ORDER BY nombre";
$query_turnos = "SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre";
$query_grados = "SELECT * FROM grados WHERE activo = 1 ORDER BY grado";

$result_carreras = $conexion->query($query_carreras);
$result_turnos = $conexion->query($query_turnos);
$result_grados = $conexion->query($query_grados);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_carrera = $_POST['id_carrera'];
    $id_grado = $_POST['id_grado'];
    $id_turno = $_POST['id_turno'];
    $clave = $conexion->real_escape_string($_POST['clave']);
    
    // Verificar si la clave ya existe
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Grupos</title>
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

        /* Estilos específicos para el formulario */
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

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
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
            
            .form-row {
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
                    <a href="registro_grupos.php" class="nav-link active">
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
                <h2><i class="fas fa-users"></i> Registro de Grupos</h2>
            </div>

            <div class="form-container">
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <h3 class="form-title">Datos del Grupo</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Carrera:</label>
                            <select name="id_carrera" class="form-select" required>
                                <option value="">Seleccionar Carrera</option>
                                <?php while ($carrera = $result_carreras->fetch_assoc()): ?>
                                    <option value="<?php echo $carrera['id_carrera']; ?>">
                                        <?php echo $carrera['nombre']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Grado:</label>
                            <select name="id_grado" class="form-select" required>
                                <option value="">Seleccionar Grado</option>
                                <?php while ($grado = $result_grados->fetch_assoc()): ?>
                                    <option value="<?php echo $grado['id_grado']; ?>">
                                        <?php echo $grado['grado']; ?>°
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Turno:</label>
                            <select name="id_turno" class="form-select" required>
                                <option value="">Seleccionar Turno</option>
                                <?php while ($turno = $result_turnos->fetch_assoc()): ?>
                                    <option value="<?php echo $turno['id_turno']; ?>">
                                        <?php echo $turno['nombre']; ?> (<?php echo $turno['abreviatura']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Clave del Grupo (ej: ISC1001-V):</label>
                            <input type="text" name="clave" class="form-input" 
                                   placeholder="Ej: ISC1001-V, LIAF110-M" required
                                   pattern="[A-Z]{3,5}\d{4}-[MVX]" 
                                   title="Formato: 3-5 letras mayúsculas, 4 dígitos, guión y turno (M, V o X)">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Registrar Grupo
                    </button>
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
            
            // Generar clave automáticamente basada en selecciones
            const carreraSelect = document.querySelector('select[name="id_carrera"]');
            const gradoSelect = document.querySelector('select[name="id_grado"]');
            const turnoSelect = document.querySelector('select[name="id_turno"]');
            const claveInput = document.querySelector('input[name="clave"]');
            
            // Mapeo de abreviaturas de carrera (deberías cargarlo desde la BD)
            const abreviaturas = {
                '1': 'ISC', '2': 'PSI', '3': 'ADM', '4': 'CON',
                '5': 'DER', '6': 'MED', '7': 'ARQ', '8': 'ICV',
                '9': 'IIN', '10': 'MER', '11': 'DIG', '12': 'ENF',
                '13': 'NUT', '14': 'ODO', '15': 'PED', '16': 'TUR',
                '17': 'GAS', '18': 'COM', '19': 'BIO', '20': 'QUI'
            };
            
            function generarClave() {
                const carreraId = carreraSelect.value;
                const gradoId = gradoSelect.value;
                const turnoId = turnoSelect.value;
                
                if (carreraId && gradoId && turnoId) {
                    // Obtener abreviatura de turno
                    const turnoOption = turnoSelect.options[turnoSelect.selectedIndex];
                    const turnoText = turnoOption.textContent;
                    const turnoAbrev = turnoText.match(/\(([MVX])\)/)[1];
                    
                    // Generar clave
                    const abreviatura = abreviaturas[carreraId] || 'GEN';
                    const grado = gradoSelect.options[gradoSelect.selectedIndex].text.match(/\d+/)[0];
                    const contador = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                    
                    claveInput.value = `${abreviatura}${grado}${contador}-${turnoAbrev}`;
                }
            }
            
            carreraSelect.addEventListener('change', generarClave);
            gradoSelect.addEventListener('change', generarClave);
            turnoSelect.addEventListener('change', generarClave);
        });
    </script>
</body>
</html>