<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Blog PME') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/assets/css/style.css">

  <!-- Estilo embutido -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #eee;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    .navbar-brand {
      font-weight: 600;
      font-size: 1.25rem;
      color: #0d6efd !important; /* azul Bootstrap */
    }

    .nav-link {
      color: #333 !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #0d6efd !important;
    }

    .card {
      border-radius: 1rem;
      border: none;
    }

    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
    }

    .modal-backdrop {
      opacity: 0.6 !important;
    }

    footer {
      background-color: #f9f9f9;
      border-top: 1px solid #eaeaea;
      padding: 20px 0;
      text-align: center;
      font-size: 0.875rem;
      color: #6c757d;
    }

    @media (max-width: 576px) {
      .navbar-brand {
        font-size: 1rem;
      }

      .nav-link {
        font-size: 0.95rem;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
      <a class="navbar-brand" href="/blog">
        <i class="bi bi-journal-text me-1"></i> Blog PME
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a href="/user/profile" class="nav-link">
              <i class="bi bi-person-circle me-1"></i> <?= esc(session()->get('user_name')) ?>
            </a>
          </li>
          <li class="nav-item">
            <a href="/logout" class="nav-link">
              <i class="bi bi-box-arrow-right me-1"></i> Sair
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Conteúdo principal -->
  <main>
    <?= $this->renderSection('content') ?>
  </main>

  <!-- Rodapé -->
  <footer>
    <div class="container">
      <small>&copy; <?= date('Y') ?> Blog PME · Presidente Prudente</small>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
