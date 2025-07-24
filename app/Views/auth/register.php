<?= view('partials/header') ?>

<div class="container mt-5">
    <h2>Cadastro de Usuário</h2>

    <?php if (session('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertErrors">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <form method="post" action="/register" id="registerForm" novalidate>
        <div class="mb-3">
            <label>Nome Completo</label>
            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required minlength="3">
            <div class="invalid-feedback">Informe um nome completo válido (mínimo 3 caracteres).</div>
        </div>
        <div class="mb-3">
            <label>Nome de usuário</label>
            <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required minlength="3">
            <div class="invalid-feedback">Informe um nome de usuário válido (mínimo 3 caracteres).</div>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            <div class="invalid-feedback">Informe um e-mail válido.</div>
        </div>
        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>">
        </div>
        <div class="mb-3">
            <label>Senha</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            <div class="invalid-feedback">Senha deve ter no mínimo 6 caracteres.</div>
        </div>

        <button class="btn btn-primary">Cadastrar</button>
    </form>
</div>

<script>
  (() => {
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });

    // Esconder alertas depois de 5 segundos
    const alertErrors = document.getElementById('alertErrors');
    if(alertErrors) {
      setTimeout(() => {
        alertErrors.classList.remove('show');
        alertErrors.classList.add('hide');
      }, 5000);
    }
  })();
</script>

<?= view('partials/footer') ?>
