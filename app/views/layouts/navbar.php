<?php

require_once __DIR__ . '/../../core/Auth.php';

$user = Auth::user();

?>

<div class="navbar">

    Welcome,
    <strong>
        <?php echo $user['name']; ?>
    </strong>

</div>
<?php
$user = Auth::user();

$profileImage = !empty($user['profile_picture'])
    ? "uploads/profiles/" . $user['profile_picture']
    : "https://ui-avatars.com/api/?name=" . urlencode($user['name']);
?>

<img src="<?= $profileImage ?>"
     style="
        width:40px;
        height:40px;
        border-radius:50%;
        object-fit:cover;
     ">