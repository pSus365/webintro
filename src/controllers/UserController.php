<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class UserController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function profile()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $user = $this->userRepository->getUser($_SESSION['user_id']);
        $this->render('user', ['user' => $user]);
    }

    public function update()
    {
        if ($this->isPost()) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                header("Location: /login");
                exit();
            }

            $name = $_POST['name'];
            $surname = $_POST['surname'];

            $this->userRepository->updateUser($_SESSION['user_id'], $name, $surname);

            header("Location: /user");
            exit();
        }
    }

    public function uploadAvatar()
    {
        if ($this->isPost() && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                header("Location: /login");
                exit();
            }

            $uploadDir = 'public/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . time() . '.' . $extension;
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                $this->userRepository->updateAvatar($_SESSION['user_id'], '/' . $targetFile);
            }

            header("Location: /user");
            exit();
        }
    }

    public function changePassword()
    {
        if (!$this->isPost()) {
            return;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $password = $_POST['password'];
        $repeatedPassword = $_POST['repeatedPassword'];

        if ($password !== $repeatedPassword) {
            // TODO: Handle error properly
            header("Location: /user");
            exit();
        }

        // Ideally we should verify old password here too, but for MVPs often direct change is allowed or old pwd is required.
        // User request didn't specify old password check, but "option to change password".
        // I'll stick to simple update for now, to match request "option to change password".

        $this->userRepository->updatePassword($_SESSION['user_id'], password_hash($password, PASSWORD_DEFAULT));
        header("Location: /user");
        exit();
    }
}
