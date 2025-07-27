<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= esc($title ?? 'Cadastro') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm rounded-3">
          <div class="card-body">
            <h4 class="mb-4 text-center">Criar Conta</h4>

            <?php if (session('errors')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertErrors">
                <ul class="mb-0">
                  <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                  <?php endforeach ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif ?>

            <form method="post" action="/register" id="registerForm" novalidate>
              <div class="mb-3">
                <label class="form-label">Nome completo</label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required minlength="3">
                <div class="invalid-feedback">Informe um nome válido (mínimo 3 caracteres).</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Usuário</label>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required minlength="3">
                <div class="invalid-feedback">Informe um nome de usuário (mínimo 3 caracteres).</div>
              </div>

              <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                <div class="invalid-feedback">Informe um e-mail válido.</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required minlength="6">
                <div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>
              </div>

              <div class="d-grid">
                <button class="btn btn-success">Cadastrar</button>
              </div>
            </form>

          </div>
        </div>

        <!-- Botão fora do card -->
        <div class="text-center mt-3">
          <a href="/" class="btn btn-link text-decoration-none">
            <i class="bi bi-house-door-fill me-1"></i> Voltar à página inicial
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function() {
      $('#registerForm').on('submit', function(e) {
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        $(this).addClass('was-validated');
      });

      const $alert = $('#alertErrors');
      if ($alert.length) {
        setTimeout(() => {
          $alert.alert('close');
        }, 5000);
      }
    });
  </script>
</body>

</html>