<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>
        <?= $pageTitle ?? 'Fleet Manager' ?>
    </title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="/public/styles/common.css">
    <?php if (isset($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <div class="app-container">
        <div class="main-wrapper">
            <!-- Global Header -->
            <header class="top-header">
                <div class="logo-section">
                    <div class="logo-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z"></path>
                            <path d="M12 12L2 7"></path>
                            <path d="M12 12L22 7"></path>
                            <path d="M12 12V22"></path>
                            <path d="M7 4.5L17 9.5"></path>
                        </svg>
                    </div>
                    <a href="/dashboard" style="text-decoration:none; color:inherit;">
                        <h2 class="logo-text">Fleet Manager</h2>
                    </a>
                </div>
                <div class="header-actions">
                    <nav class="main-nav">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/dashboard') ? 'active' : '' ?>"
                            href="/dashboard">Dashboard</a>
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/vehicles') !== false) ? 'active' : '' ?>"
                            href="/vehicles">Pojazdy</a>
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/drivers') !== false) ? 'active' : '' ?>"
                            href="/drivers">Kierowcy</a>
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/maintenance') !== false) ? 'active' : '' ?>"
                            href="/maintenance">Utrzymanie</a>
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/raports') !== false) ? 'active' : '' ?>"
                            href="/raports">Raporty</a>
                    </nav>
                    <div class="user-actions">
                        <!-- Notification logic can go here -->
                        <a href="/reminders" class="btn-icon"
                            style="background-color: var(--primary-color); color: white;">
                            <span class="material-symbols-outlined"> notifications </span>
                        </a>
                        <a href="/user" class="btn-icon">
                            <span class="material-symbols-outlined">account_circle</span>
                        </a>
                        <form action="/logout" method="POST" style="display:inline;">
                            <button type="submit" class="btn-icon" title="Wyloguj">
                                <span class="material-symbols-outlined">logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="content-area">