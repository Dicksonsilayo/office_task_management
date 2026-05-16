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