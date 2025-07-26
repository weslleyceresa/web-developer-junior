<?= $this->extend('partials/layout_public') ?>
<?= $this->section('content') ?>

<style>
  .text-primary-custom {
    color: #0d6efd;
  }

  .text-primary-custom:hover {
    color: #0b5ed7;
  }

  .hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex-direction: column;
  }

  .hero-title {
    font-size: 2.75rem;
    font-weight: 700;
    color: #0d6efd;
  }

  .hero-subtitle {
    color: #6c757d;
    font-size: 1.125rem;
  }

  .hero-links a {
    font-weight: 500;
    color: #0d6efd;
    text-decoration: none;
  }

  .hero-links a:hover {
    color: #0b5ed7;
    text-decoration: underline;
  }
</style>

<div class="container hero-section">
  <h1 class="hero-title mb-3">Bem-vindo ao <span class="text-primary">Blog PME</span></h1>
  <p class="hero-subtitle mb-4">
    Seu portal de informação sobre pequenas e médias empresas de Presidente Prudente.
  </p>

  <div class="hero-links">
    <p class="mb-2">
      Já possui uma conta?
      <a href="/login">Clique aqui para entrar</a>.
    </p>
    <p>
      Ainda não tem cadastro?
      <a href="/register">Crie sua conta agora mesmo!</a>
    </p>
  </div>
</div>

<?= $this->endSection() ?>
