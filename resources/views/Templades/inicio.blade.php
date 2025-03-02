@extends('dashboard')

@section('title', 'Inicio')

@section('css')
<style>
    .bg-primary-dark {
        background-color: rgba(0, 0, 0, 0.15);
    }
    .bg-success-dark {
        background-color: rgba(0, 0, 0, 0.15);
    }
    .bg-warning-dark {
        background-color: rgba(0, 0, 0, 0.15);
    }
    .bg-danger-dark {
        background-color: rgba(0, 0, 0, 0.15);
    }
    .card-footer {
        border-top: none;
        padding: 0.75rem 1.25rem;
    }
    .welcome-card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .welcome-icon {
        font-size: 4rem;
        opacity: 0.8;
    }
    .action-button {
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    @auth
        @if(Auth::user()->rol == 'admin')
        <!-- CONTENIDO PARA ADMINISTRADORES -->
        <div class="row mb-4">
            <div class="col-12">
                <h1>Panel de administración</h1>
            </div>
        </div>

        <div class="row">
            <!-- Tarjeta de Marcas -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold">{{ $totalMarcas }}</h3>
                            <p class="mb-0">Marcas Registradas</p>
                        </div>
                        <i class="fa-solid fa-tag fa-3x opacity-50"></i>
                    </div>
                    
                    <a href="{{ route('marcas') }}" class="text-white text-decoration-none">
                        <div class="card-footer bg-primary-dark d-flex justify-content-between align-items-center">
                            Ver detalles
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tarjeta de Categorías -->
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold">{{ $totalCategorias }}</h3>
                            <p class="mb-0">Categorías Registradas</p>
                        </div>
                        <i class="fa-solid fa-list fa-3x opacity-50"></i>
                    </div>
                    
                    <a href="{{ route('categorias') }}" class="text-white text-decoration-none">
                        <div class="card-footer bg-success-dark d-flex justify-content-between align-items-center">
                            Ver detalles
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tarjeta de Usuarios -->
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold">{{ $totalUsuarios }}</h3>
                            <p class="mb-0">Usuarios Registrados</p>
                        </div>
                        <i class="fa-solid fa-users fa-3x opacity-50"></i>
                    </div>
                    <a href="{{ route('usuarios') }}" class="text-white text-decoration-none">
                        <div class="card-footer bg-success-dark d-flex justify-content-between align-items-center">
                            Ver detalles
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tarjeta de Productos -->
            <div class="col-md-3 mb-4">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="display-4 fw-bold">{{ $totalProductos }}</h3>
                            <p class="mb-0">Productos registrados</p>
                        </div>
                        <i class="fa-solid fa-box fa-3x opacity-50"></i>
                    </div>
                    <a href="{{ route('productos') }}" class="text-white text-decoration-none">
                        <div class="card-footer bg-success-dark d-flex justify-content-between align-items-center">
                            Ver detalles
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- CONTENIDO PARA USUARIOS NORMALES -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card welcome-card bg-light">
                    <div class="card-body p-5 text-center">
                        <i class="fa-solid fa-sun welcome-icon text-warning mb-4"></i>
                        <h1 class="display-4 mb-4">¡Buenos días, {{ Auth::user()->name }}!</h1>
                        <p class="lead mb-5">Bienvenido al sistema de ventas. Desde aquí podrás registrar todas tus transacciones de venta.</p>
                        
                        <a href="{{ route('ventas') }}" class="btn btn-primary btn-lg action-button">
                            <i class="fa-solid fa-basket-shopping me-2"></i>
                            Registrar Ventas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth
</div>
@endsection