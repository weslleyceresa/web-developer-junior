<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title><?= esc($title ?? 'Blog PME') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html,
    body {
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
      background-color: #fff;
      border-bottom: 1px solid #eee;
    }

    .navbar-brand {
      font-weight: bold;
      color: #f47c20 !important;
    }

    .nav-link {
      color: #555 !important;
    }

    .nav-link:hover {
      color: #f47c20 !important;
    }

    .btn-primary {
      background-color: #f47c20;
      border-color: #f47c20;
    }

    .btn-primary:hover {
      background-color: #e46c0f;
      border-color: #e46c0f;
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