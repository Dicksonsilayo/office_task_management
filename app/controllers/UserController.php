    <?php
    require_once __DIR__. "/../core/Flash.php";
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../configs/database.php';
    require_once __DIR__ . '/../core/Auth.php';
    require_once __DIR__ . '/../core/Guard.php';

    class UserController
    {
        private $userModel;

        public function __construct()
        {
            $this->userModel = new User();
        }

        /*
        |--------------------------------------------------------------------------
        | LIST USERS
        |--------------------------------------------------------------------------
        */
        public function index()
        {
            Guard::adminOnly(); // ONLY admin sees users

            $users = $this->userModel->getAll();

            require __DIR__ . '/../views/user/index.php';
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE FORM
        |--------------------------------------------------------------------------
        */

        public function create()
{
    Guard::adminOnly();

    $db = (new Database())->connect();

    // DEBUG SAFE: ensure roles exist
    $roles = [];
    $departments = [];

    $roleResult = $db->query("SELECT id, name FROM roles ORDER BY name ASC");
    if ($roleResult) {
        $roles = $roleResult->fetch_all(MYSQLI_ASSOC);
    }

    $deptResult = $db->query("SELECT id, name FROM departments ORDER BY name ASC");
    if ($deptResult) {
        $departments = $deptResult->fetch_all(MYSQLI_ASSOC);
    }
  

    require __DIR__ . '/../views/user/create.php';
}
        /*
        |--------------------------------------------------------------------------
        | STORE USER
        |--------------------------------------------------------------------------
        */
        public function store()
{
    Guard::adminOnly();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    // =========================
    // SANITIZE INPUT
    // =========================
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $role_id = $_POST['role_id'] ?? null;

    // =========================
    // VALIDATION
    // =========================
    if (strlen($name) < 3 || strlen($name) > 100) {
       Flash::set('error','name shold not exceed 100 character and should not be less than 3');
       
        header("Location: index.php?page=create_user");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Flash::set('error','Invalid email format');
        header("Location: index.php?page=create_user");
        exit;
    }

    if (strlen($email) > 150) {
      Flash::set('error','Email too long');
        header("Location: index.php?page=create_user");
        exit;
    }
  if ($this->userModel->emailExists($email)) {

    Flash::set('error', 'This email is already used');

    header("Location: index.php?page=create_user");
    exit;
}

    if (strlen($password) < 6 || strlen($password) > 50) {
        Flash::set('error','Password must be 6–50 characters');
        header("Location: index.php?page=create_user");
        exit;
    }

    if ($password !== $confirm) {
        Flash::set('error','Passwords do not match');
        header("Location: index.php?page=create_user");
        exit;
    }

    if (!is_numeric($department_id) || !is_numeric($role_id)) {
       Flash::set('error','Invalid department or role');
        header("Location: index.php?page=create_user");
        exit;
    }

  $data = [
    'name' => $name,
    'email' => $email,
    'password' => $password,
    'department_id' => $department_id,
    'role_id' => $role_id
];



    $this->userModel->create($data);

    Flash::set('success','User created successfully');

    header("Location: index.php?page=create_user");
    exit;
}

        /*
        |--------------------------------------------------------------------------
        | EDIT USER
        |--------------------------------------------------------------------------
        */
public function edit()

{
    Guard::adminOnly();

    if (!isset($_GET['id'])) {
        die("User ID missing");
    }

    $id = (int) $_GET['id'];

    $db = (new Database())->connect();

    /*
    |--------------------------------------------------------------------------
    | GET USER
    |--------------------------------------------------------------------------
    */
    $stmt = $db->prepare("
        SELECT * FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editUser = $stmt->get_result()->fetch_assoc();

    if (!$editUser) {
        die("User not found");
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER ROLE FROM PIVOT TABLE
    |--------------------------------------------------------------------------
    */
    $roleStmt = $db->prepare("
        SELECT role_id
        FROM role_user
        WHERE user_id = ?
        LIMIT 1
    ");

    $roleStmt->bind_param("i", $id);
    $roleStmt->execute();

    $roleData = $roleStmt->get_result()->fetch_assoc();

    // attach role_id to user array
    $editUser['role_id'] = $roleData['role_id'] ?? null;

    /*
    |--------------------------------------------------------------------------
    | GET DEPARTMENTS
    |--------------------------------------------------------------------------
    */
    $departments = $db->query("
        SELECT * FROM departments
        ORDER BY name ASC
    ")->fetch_all(MYSQLI_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | GET ROLES
    |--------------------------------------------------------------------------
    */
    $roles = $db->query("
        SELECT * FROM roles
        ORDER BY name ASC
    ")->fetch_all(MYSQLI_ASSOC);

    require __DIR__ . '/../views/user/edit.php';
}

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */
    public function update()

{
    Guard::adminOnly();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $role_id = $_POST['role_id'] ?? null;

    if ($id <= 0) {
        Flash::set('error','Invalid user ID');
        header("Location: index.php?page=users");
        exit;
    }

    if (strlen($name) < 3 || strlen($name) > 100) {
        Flash::set('error','Invalid name length');
        header("Location: index.php?page=edit_user&id=$id");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Flash::set('error','Invalid email');
        header("Location: index.php?page=edit_user&id=$id");
        exit;
    }

    if (!empty($password)) {

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password too short";
            header("Location: index.php?page=edit_user&id=$id");
            exit;
        }

        if ($password !== $confirm) {
          Flash::set('error','Passwords do not match');
            header("Location: index.php?page=edit_user&id=$id");
            exit;
        }
    }

    $this->userModel->update([
        'id' => $id,
        'name' => htmlspecialchars($name),
        'email' => strtolower($email),
        'password' => $password,
        'department_id' => (int)$department_id,
        'role_id' => (int)$role_id
    ]);

    Flash::set('success','User updated successfully');
    header("Location: index.php?page=edit_user&id=" . $id);

    exit;

}

        /*
        |--------------------------------------------------------------------------
        | DELETE USER
        |--------------------------------------------------------------------------
        */
        public function delete()
        {
            Guard::adminOnly();

            $this->userModel->delete($_GET['id']);

            Flash::set('success','User deleted successfully');

            header("Location: index.php?page=users");
            exit;
        }
    }