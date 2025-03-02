<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-VhY6z+AN2lnR2p8JdE7ErTX9BtuUq3e61CXF64Rxmfjxsk/rfRAfQeDjG3TfdnZ0" crossorigin="anonymous">

</head>
<body class="bg-light">

    <main class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 400px;">
            <h3 class="text-center"><i class="fa-solid fa-user"></i></h3>
            <h3 class="text-center">Iniciar Sesión</h3>
            <form method="POST" action="{{ route('inicia-sesion') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="emailInput" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="email" id="emailInput" name="email" class="form-control" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="passwordInput" class="form-label"><i class="fa-solid fa-key"></i> Password</label>
                    <input type="password" id="passwordInput" name="password" class="form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberCheck" name="remember">
                    <label class="form-check-label" for="rememberCheck">Mantener sesión iniciada</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>

                <p class="text-center mt-3">
                    No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a>
                </p>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
