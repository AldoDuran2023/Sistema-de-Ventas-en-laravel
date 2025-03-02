<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de ventas')</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        
        body {
            font-family: Arial, sans-serif;
        }

        .nav-treeview {
            padding-left: 15px;
        }

        .nav-treeview .nav-item a {
            padding-left: 30px; /* Mueve las sublistas más a la derecha */
        }

        .sidebar {
            overflow: hidden;
        }
    </style>

    @yield('css')
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="/"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto me-3">
                <!-- Usuario Logueado -->
                @auth
                    <li class="nav-item d-flex align-items-center me-3">
                        <div class="d-flex align-items-center bg-light px-3 py-2 rounded shadow-sm">
                            <i class="fas fa-user-circle fa-lg me-2 text-primary"></i>
                            <h6 class="mb-0 text-dark fw-bold">Hola, {{ Auth::user()->name }}</h6>
                        </div>
                    </li>
                @endauth

                <!-- Botón de Cerrar Sesión -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-2"></i> Salir
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('home') }}" class="brand-link">
                <span class="brand-text font-weight-light"><i class="fa-solid fa-shop"></i> Sistema de ventas</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>
                        
                        @auth
                            @if(Auth::user()->rol == 'admin')
                            <!-- MENÚ SÓLO PARA ADMINISTRADORES -->
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-list-check"></i>
                                    <p>Administrador<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('categorias') }}" class="nav-link">
                                            <i class="fa-solid fa-leaf"></i>
                                            <p>Categorias</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('marcas') }}" class="nav-link">
                                            <i class="fa-solid fa-tag"></i>
                                            <p>Marcas</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('productos') }}" class="nav-link">
                                            <i class="fa-solid fa-barcode"></i>
                                            <p>Productos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('usuarios') }}" class="nav-link">
                                            <i class="fa-solid fa-user-tie"></i>
                                            <p>Usuarios</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-shop"></i>
                                    <p>Compras<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('proveedores') }}" class="nav-link">
                                            <i class="fa-solid fa-phone"></i>
                                            <p>Proveedores</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('compras') }}" class="nav-link">
                                            <i class="fa-solid fa-truck"></i>
                                            <p>Pedidos</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>Reportes<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('reporte.diario') }}" target="_blank" class="nav-link">
                                            <i class="fa-solid fa-file"></i>
                                            <p>Ventas del dia</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            
                            <!-- MENÚ PARA TODOS LOS USUARIOS -->
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <p>Ventas<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('ventas') }}" class="nav-link">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <p>registrar</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

        // Marcar el menú activo basado en la URL
        let currentUrl = window.location.href;
            $('.nav-item a').each(function () {
                if (this.href === currentUrl) {
                    $(this).addClass('active'); // Resaltar el enlace activo
                    $(this).closest('.has-treeview').addClass('menu-open'); // Asegurar que el menú padre esté abierto
                    $(this).closest('.nav-treeview').show(); // Mostrar el submenú si es necesario
                }
            });
        });

    </script>

    @yield('js')
</body>
</html>