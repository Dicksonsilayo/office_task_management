<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="goal-page">

    <div class="goal-card">

        <!-- HEADER -->
        <div class="goal-header">
            <div>
                <h1>Create Goal</h1>
                <p>Define organizational goals clearly and track progress</p>
            </div>
        </div>

        <!-- ALERTS -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                ⚠️ <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                ✅ <?= $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" action="index.php?page=store_goal" class="goal-form">

            <div class="form-group">
                <label>Goal Name</label>
                <input type="text" name="name" placeholder="Enter goal name" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="6" placeholder="Describe the goal..."></textarea>
            </div>

            <button class="btn-submit">
                Save Goal
            </button>

        </form>

    </div>

</div>

<style>

/* PAGE LAYOUT */
.goal-page{
    min-height:100vh;
    background:#f1f5f9;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:50px 20px;
}

/* CARD */
.goal-card{
    width:100%;
    max-width:650px;
    background:#fff;
    border-radius:18px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* HEADER */
.goal-header h1{
    margin:0;
    font-size:28px;
    color:#0f172a;
}

.goal-header p{
    margin-top:5px;
    color:#64748b;
}

/* ALERTS */
.alert{
    padding:14px 16px;
    border-radius:12px;
    margin:15px 0;
    font-weight:500;
    font-size:14px;
}

.alert.error{
    background:#fee2e2;
    color:#991b1b;
}

.alert.success{
    background:#dcfce7;
    color:#166534;
}

/* FORM */
.goal-form{
    margin-top:20px;
    display:flex;
    flex-direction:column;
    gap:18px;
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
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:12px;
    font-size:15px;
    transition:0.2s;
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

/* MOBILE */
@media(max-width:768px){
    .goal-card{
        padding:25px;
    }
}

</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>