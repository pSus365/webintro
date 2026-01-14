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
        // Mock Session User ID = 1
        $user = $this->userRepository->getUser(1);
        $this->render('user', ['user' => $user]);
    }

    public function update()
    {
        if ($this->isPost()) {
            $name = $_POST['name'];
            $surname = $_POST['surname'];

            // Mock ID 1
            $this->userRepository->updateUser(1, $name, $surname);

            // Reload with updated data
            header("Location: /user");
            exit();
        }
    }

    public function uploadAvatar()
    {
        if ($this->isPost() && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $uploadDir = 'public/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . time() . '.' . $extension;
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                // Mock ID 1
                $this->userRepository->updateAvatar(1, '/' . $targetFile);
            }

            header("Location: /user");
            exit();
        }
    }
}
