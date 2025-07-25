<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Blog PME') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      background-color: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }

    main {
      flex: 1;
    }

    .modal-backdrop {
      opacity: 0.7 !important;
    }

    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand {
      font-weight: bold;
      color: #f47c20 !important;
    }

    .nav-link {
      color: #333 !important;
    }

    .nav-link:hover {
      color: #f47c20 !important;
    }

    .card {
      border-radius: 12px;
    }

    footer {
      padding: 20px 0;
      background-color: #f9f9f9;
      border-top: 1px solid #eee;
      text-align: center;
      color: #888;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
      <a class="navbar-brand" href="/blog">Blog PME</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <span class="nav-link">Olá, <?= esc(session()->get('user_name')) ?></span>
          </li>
          <li class="nav-item">
            <a href="/logout" class="nav-link">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <?= $this->renderSection('content') ?>
  </main>

  <footer>
    <div class="container">
      <small>&copy; <?= date('Y') ?> Blog PME - Presidente Prudente</small>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>