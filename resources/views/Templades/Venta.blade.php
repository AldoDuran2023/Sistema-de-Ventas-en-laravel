@extends('dashboard')

@section('title','Venta')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Registro de todas las ventas</h1>
            <form id="FormVenta" action="{{ route('Detalle') }}" method="POST">
                @csrf
                <button class="btn btn-success ms-auto" type="submit">Añadir nueva Venta</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table ">
                <table class="table table-bordered" id="tablaVenta">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Total</th>
                            <th scope="col">Gestion</th>
                            
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            
        </div>
    </div>

@endsection

@section('js')
<script>
    $(document).ready(function () {
        // Cargamos la tabla
        let table = $('#tablaVenta').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            ajax: {
                url: '/ventas/list',
                type: 'GET',
                dataSrc: 'data', 
            },
            columns: [
                {data: 'id'},
                { data: 'id_cliente' }, 
                { data: 'fecha' }, 
                { data: 'total' }, 
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<a href="/ventas/boleta/${data}" class="btn btn-primary btn-sm" target="_blank">Ver Boleta</a>`;
                    }
                }
            ]
        });
    });
</script> 
@endsection