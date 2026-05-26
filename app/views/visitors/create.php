<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="visitor-page">
 
    <div class="visitor-card">
<a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
        <!-- HEADER -->
        <div class="visitor-header">

            <div>
                <h1>Register Visitor</h1>
                <p>Add and manage office visitors easily</p>
            </div>

            <div class="header-icon">
                👤
            </div>

        </div>

        <!-- SUCCESS -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert success-alert">
                <span>✅</span>
                <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
        <?php endif; ?>

        <!-- ERROR -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert error-alert">
                <span>⚠️</span>
                <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST"
              action="index.php?page=store_visitor"
              class="visitor-form">

            <!-- FULL NAME -->
            <div class="form-group">
                <label>Full Name</label>

                <input type="text"
                       name="full_name"
                       placeholder="Enter visitor full name"
                       required>
            </div>

            <!-- PHONE -->
            <div class="form-group">
                <label>Phone Number</label>

                <input type="text"
                       name="phone"
                       placeholder="e.g 0712345678"
                       required>
            </div>

            <!-- PURPOSE -->
            <div class="form-group">
                <label>Purpose of Visit</label>

                <textarea name="purpose"
                          rows="4"
                          placeholder="Why is the visitor here?"
                          required></textarea>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-submit">
                Register Visitor
            </button>

        </form>

    </div>

</div>

<style>

/* PAGE */

.visitor-page{
    padding:40px 20px;
    background:#f1f5f9;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
}

/* CARD */

.visitor-card{
    width:100%;
    max-width:650px;
    background:white;
    border-radius:18px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* HEADER */

.visitor-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.visitor-header h1{
    margin:0;
    font-size:28px;
    color:#0f172a;
}

.visitor-header p{
    margin-top:5px;
    color:#64748b;
}

.header-icon{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
}

/* ALERTS */

.alert{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 16px;
    border-radius:12px;
    margin-bottom:20px;
    animation:fadeIn .4s ease;
}

.alert p{
    margin:0;
    font-size:14px;
    font-weight:500;
}

.success-alert{
    background:#dcfce7;
    color:#166534;
}

.error-alert{
    background:#fee2e2;
    color:#991b1b;
}

/* FORM */

.visitor-form{
    display:flex;
    flex-direction:column;
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:8px;
    font-weight:600;
    color:#334155;
}

.form-group input,
.form-group textarea{
    width:100%;
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    font-size:15px;
    transition:0.2s;
    background:#fff;
}

.form-group input:focus,
.form-group textarea:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.1);
}

/* BUTTON */

.btn-submit{
    margin-top:10px;
    background:#2563eb;
    color:white;
    border:none;
    padding:14px;
    border-radius:12px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-submit:hover{
    background:#1d4ed8;
    transform:translateY(-1px);
}

/* ANIMATION */

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-5px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* MOBILE */

@media(max-width:768px){

    .visitor-card{
        padding:25px;
    }

    .visitor-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

}

</style>

<script>

/*
|--------------------------------------------------------------------------
| AUTO HIDE ALERTS
|--------------------------------------------------------------------------
*/
setTimeout(() => {

    document.querySelectorAll('.alert').forEach(alert => {

        alert.style.transition = "0.4s";
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-5px)";

        setTimeout(() => {
            alert.remove();
        }, 400);

    });

}, 4000);

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>