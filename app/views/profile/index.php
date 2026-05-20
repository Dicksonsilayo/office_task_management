<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php $user = Auth::user(); ?>
<style>.profile-page {
    padding: 30px;
}

.profile-card {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    max-width: 650px;
    margin: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.profile-wrapper {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 20px;
}

.image-box,
.camera-box {
    flex: 1;
    text-align: center;
}

.profile-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #eee;
}

.no-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

video {
    width: 180px;
    height: 180px;
    border-radius: 12px;
    border: 2px solid #ddd;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.btn-submit {
    background: #2563eb;
    color: white;
    border: none;
    padding: 12px 18px;
    border-radius: 10px;
    cursor: pointer;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px 18px;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 10px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}</style>
<div class="profile-page">

    <!-- SUCCESS MESSAGE -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="profile-card">

        <!-- PROFILE + CAMERA WRAPPER -->
        <div class="profile-wrapper">

            <!-- PROFILE IMAGE -->
            <div class="image-box">

                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="uploads/profiles/<?= $user['profile_picture'] ?>"
                         class="profile-image">
                <?php else: ?>
                    <div class="no-image">No Photo</div>
                <?php endif; ?>

            </div>

            <!-- CAMERA BOX -->
            <div class="camera-box">

                <video id="video" autoplay playsinline style="display:none;"></video>
                <canvas id="canvas" style="display:none;"></canvas>

                <input type="hidden" name="captured_image" id="captured_image_form">

                <button type="button" class="btn-submit" onclick="startCamera()">
                    Start Camera
                </button>

                <button type="button"
                        id="captureBtn"
                        class="btn-danger"
                        style="display:none; margin-top:10px;"
                        onclick="takePhoto()">
                    Capture Photo
                </button>

            </div>

        </div>

        <!-- FORM -->
        <form method="POST"
              action="index.php?page=update_profile"
              enctype="multipart/form-data">

            <div class="form-group">
                <label>Upload Profile Picture</label>
                <input type="file" name="profile_picture">
            </div>

            <!-- CAMERA DATA -->
            <input type="hidden" name="captured_image" id="captured_image_form">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text"
                       name="name"
                       value="<?= htmlspecialchars($user['name']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       value="<?= htmlspecialchars($user['email']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text"
                       name="phone"
                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <button type="submit" class="btn-submit">
                Update Profile
            </button>

        </form>

    </div>
</div>
<script>
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let stream = null;
let captureBtn = document.getElementById('captureBtn');

// START CAMERAlet stream = null;

async function startCamera() {

    const video = document.getElementById('video');
    const captureBtn = document.getElementById('captureBtn');

    try {

        // STOP OLD STREAM IF EXISTS
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        // START NEW CAMERA
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "user"
            },
            audio: false
        });

        video.srcObject = stream;

        // IMPORTANT: ensure video is visible
        video.style.display = "block";
        video.play();

        // show capture button
        captureBtn.style.display = "inline-block";

    } catch (err) {
        console.error(err);
        alert("Camera failed. Please allow permission or use HTTPS.");
    }
}
// AUTO HIDE SUCCESS MESSAGE
document.addEventListener("DOMContentLoaded", () => {

    const success = document.querySelector('.alert-success');

    if (success) {
        setTimeout(() => {
            success.style.transition = "0.5s";
            success.style.opacity = "0";
            setTimeout(() => success.remove(), 500);
        }, 5000);
    }

});
</script>