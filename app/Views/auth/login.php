<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= esc($title ?? 'Login') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm rounded-3">
          <div class="card-body">
            <h4 class="mb-4 text-center">Entrar no Painel</h4>

            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertError">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form action="/do-login" method="post" id="loginForm" novalidate>
              <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus />
                <div class="invalid-feedback">Informe o nome de usuário.</div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required />
                <div class="invalid-feedback">Informe a senha.</div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function() {
      $('#loginForm').on('submit', function(e) {
        // Validação HTML5 personalizada
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
          $(this).addClass('was-validated');
        }
      });

      // Esconder alerta após 5 segundos
      const $alertError = $('#alertError');
      if ($alertError.length) {
        setTimeout(() => {
          $alertError.alert('close');
        }, 5000);
      }
    });
  </script>
</body>
</html>
