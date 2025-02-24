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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Pagina de inicio</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
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

        <!-- Puedes agregar más tarjetas según necesites -->
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="display-4 fw-bold">0</h3>
                        <p class="mb-0">Usuarios Registrados</p>
                    </div>
                    <i class="fa-solid fa-users fa-3x opacity-50"></i>
                </div>
                <div class="card-footer bg-warning-dark d-flex justify-content-between align-items-center">
                    <a href="#" class="text-white text-decoration-none">
                        Ver detalles
                    </a>
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="display-4 fw-bold">0</h3>
                        <p class="mb-0">Productos</p>
                    </div>
                    <i class="fa-solid fa-box fa-3x opacity-50"></i>
                </div>
                <div class="card-footer bg-danger-dark d-flex justify-content-between align-items-center">
                    <a href="#" class="text-white text-decoration-none">
                        Ver detalles
                    </a>
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

