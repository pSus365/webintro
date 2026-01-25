<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../models/Csrf.php';

class SecurityController extends AppController
{
    public function login()
    {
        $userRepository = UserRepository::getInstance();

        if (!$this->isPost()) {
            return $this->render('login', ['csrf_token' => Csrf::generateToken()]);
        }

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !Csrf::isValid($_POST['csrf_token'])) {
            return $this->render('login', ['messages' => ['Invalid Session (CSRF). Please refresh.'], 'csrf_token' => Csrf::generateToken()]);
        }

        // Rate Limiting
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
            if (time() - $_SESSION['last_attempt_time'] < 300) { // 5 minutes
                return $this->render('login', ['messages' => ['Too many failed attempts. Try again in 5 minutes.'], 'csrf_token' => Csrf::generateToken()]);
            } else {
                // Reset after timeout
                unset($_SESSION['login_attempts']);
                unset($_SESSION['last_attempt_time']);
            }
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $userRepository->getUserByEmail($email);

        // Generic error message for security
        $errorMessage = 'Email or password incorrect.';

        if (!$user) {
            $this->incrementLoginAttempts();
            return $this->render('login', ['messages' => [$errorMessage], 'csrf_token' => Csrf::generateToken()]);
        }

        if (!password_verify($password, $user['password'])) {
            $this->incrementLoginAttempts();
            return $this->render('login', ['messages' => [$errorMessage], 'csrf_token' => Csrf::generateToken()]);
        }

        // Login Success
        session_regenerate_id(true); // Prevent Session Fixation
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'] . ' ' . $user['surname'];
        $_SESSION['user_role'] = $user['role'];

        // Reset attempts
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt_time']);

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        exit();
    }

    private function incrementLoginAttempts()
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register', ['csrf_token' => Csrf::generateToken()]);
        }

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !Csrf::isValid($_POST['csrf_token'])) {
            return $this->render('register', ['messages' => ['Invalid Session (CSRF).'], 'csrf_token' => Csrf::generateToken()]);
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $messages = [];

        // Validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = 'Invalid email format.';
        }

        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
            $messages[] = 'Password must be at least 8 chars, contain 1 uppercase, 1 lowercase, and 1 number.';
        }

        if ($password !== $confirmedPassword) {
            $messages[] = 'Passwords do not match.';
        }

        $userRepository = UserRepository::getInstance();
        if ($userRepository->getUserByEmail($email)) {
            $messages[] = 'Email already exists.';
        }

        if (!empty($messages)) {
            return $this->render('register', ['messages' => $messages, 'csrf_token' => Csrf::generateToken()]);
        }

        $userRepository->addUser($name, $surname, $email, password_hash($password, PASSWORD_DEFAULT));

        return $this->render('login', ['messages' => ['You\'ve been succesfully registered!'], 'csrf_token' => Csrf::generateToken()]);
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session values
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
        exit();
    }
}