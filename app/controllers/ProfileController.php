<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../configs/database.php';

class ProfileController extends Controller
{
    public function index()
    {
        $this->view('profile/index');
    }

    public function update()
    {
        $db = new Database();
        $conn = $db->connect();

        $user = Auth::user();
        $id = $user['id'];

        $name  = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $profilePicture = $user['profile_picture'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | CAMERA IMAGE (BASE64)
        |--------------------------------------------------------------------------
        */
        if (!empty($_POST['captured_image'])) {

            $image = $_POST['captured_image'];
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $fileData = base64_decode($image);

            $filename = time() . '_camera.png';

            $path = __DIR__ . '/../../public/uploads/profiles/' . $filename;

            file_put_contents($path, $fileData);

            $profilePicture = $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOAD
        |--------------------------------------------------------------------------
        */
        if (!empty($_FILES['profile_picture']['name'])) {

            $file = $_FILES['profile_picture'];

            $filename = time() . '_' . basename($file['name']);

            $target = __DIR__ . '/../../public/uploads/profiles/' . $filename;

            move_uploaded_file($file['tmp_name'], $target);

            $profilePicture = $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */
        $stmt = $conn->prepare("
            UPDATE users
            SET name = ?, email = ?, phone = ?, profile_picture = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $name,
            $email,
            $phone,
            $profilePicture,
            $id
        );

        $stmt->execute();

        /*
        |--------------------------------------------------------------------------
        | REFRESH SESSION
        |--------------------------------------------------------------------------
        */
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['profile_picture'] = $profilePicture;

        $_SESSION['success'] = "Profile updated successfully";

        header("Location: index.php?page=profile");
        exit;
    }
}