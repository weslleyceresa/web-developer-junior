<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Blog PME') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #fff;
      color: #333;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #e9ecef;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.025);
    }

    .navbar-brand {
      font-weight: 700;
      color: #0d6efd !important;
      letter-spacing: 0.5px;
    }

    .nav-link {
      color: #444 !important;
    }

    .nav-link:hover {
      color: #0d6efd !important;
    }

    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
    }

    footer {
      padding: 20px 0;
      background-color: #f8f9fa;
      border-top: 1px solid #eaeaea;
      text-align: center;
      color: #6c757d;
      font-size: 0.875rem;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="/">Blog PME</a>
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
