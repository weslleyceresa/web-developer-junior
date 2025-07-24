<?= $this->extend('partials/layout_public') ?>
<?= $this->section('content') ?>

<div class="container d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 70vh;">
  <h1 class="display-5 fw-bold text-orange mb-3">Bem-vindo ao Blog PME</h1>
  <p class="lead mb-4 text-muted">O seu portal de informação sobre pequenas e médias empresas de Presidente Prudente.</p>

  <div class="mt-3">
    <p class="fs-6 text-secondary">
      Já possui uma conta?
      <a href="/login" class="text-orange fw-semibold text-decoration-none">Clique aqui para entrar</a>.
    </p>
    <p class="fs-6 text-secondary">
      Ainda não tem cadastro?
      <a href="/register" class="text-orange fw-semibold text-decoration-none">Crie sua conta agora mesmo!</a>
    </p>
  </div>
</div>

<style>
  .text-orange {
    color: #f47c20;
  }
  .text-orange:hover {
    color: #e46c0f;
  }
</style>

<?= $this->endSection() ?>
