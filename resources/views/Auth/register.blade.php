<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <main class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 400px;">
            <h3 class="text-center">Registro</h3>
            <form method="POST" action="{{ route('validar-registro') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email</label>
                    <input type="email" id="emailInput" name="email" class="form-control" required autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" id="passwordInput" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="userInput" class="form-label">Nombre</label>
                    <input type="text" id="userInput" name="name" class="form-control" required autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
