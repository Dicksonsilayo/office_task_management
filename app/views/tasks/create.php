<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="task-page">

    <div class="task-card">

        <!-- HEADER -->
        <div class="task-header">

            <div>
                <a href="javascript:history.back()" class="back-btn">
                    ← Back
                </a>

                <h1>Create Task</h1>

                <p>
                    Assign and manage organizational tasks
                </p>
            </div>

            <div class="task-icon">
                📋
            </div>

        </div>

        <!-- SUCCESS -->
        <?php if (isset($_SESSION['success'])): ?>

            <div class="alert success-alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <!-- MULTIPLE ERRORS -->
        <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>

            <div class="alert error-alert">

                <?php foreach ($_SESSION['errors'] as $err): ?>

                    <div>
                        <?= htmlspecialchars($err) ?>
                    </div>

                <?php endforeach; ?>

            </div>

            <?php unset($_SESSION['errors']); ?>

        <?php endif; ?>

        <!-- SINGLE ERROR -->
        <?php if (isset($_SESSION['error'])): ?>

            <div class="alert error-alert">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <!-- FORM -->
        <form method="POST"
              action="index.php?page=store_task"
              class="task-form">

            <div class="form-grid">

                <!-- TITLE -->
                <div class="form-group full">

                    <label>Task Title</label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter task title"
                        required
                    >

                </div>

                <!-- DESCRIPTION -->
                <div class="form-group full">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Describe the task..."
                        required
                    ></textarea>

                </div>

                <!-- PRIORITY -->
                <div class="form-group">

                    <label>Priority</label>

                    <select name="priority" required>

                        <option value="">Select Priority</option>

                        <option value="low">Low</option>

                        <option value="medium">Medium</option>

                        <option value="high">High</option>

                    </select>

                </div>

                <!-- ASSIGN USER -->
                <div class="form-group">

                    <label>Assign To</label>

                    <select name="assigned_to" required>

                        <option value="">Select User</option>

                        <?php if (!empty($users)): ?>

                            <?php foreach ($users as $user): ?>

                                <option value="<?= $user['id'] ?>">

                                    <?= htmlspecialchars($user['name']) ?>

                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>

                </div>

                <!-- GOAL -->
                <div class="form-group">

                    <label>Goal</label>

                    <select name="goal_id" required>

                        <option value="">Select Goal</option>

                        <?php if (!empty($goals)): ?>

                            <?php foreach ($goals as $goal): ?>

                                <option value="<?= $goal['id'] ?>">

                                    <?= htmlspecialchars($goal['name']) ?>

                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>

                </div>

                <!-- DEADLINE -->
                <div class="form-group">

                    <label>Deadline</label>

                    <input
                        type="date"
                        name="deadline"
                        required
                    >

                </div>

                <!-- NOTES -->
                <div class="form-group full">

                    <label>Additional Notes</label>

                    <textarea
                        name="notes"
                        placeholder="Optional notes..."
                    ></textarea>

                </div>

            </div>

            <!-- BUTTON -->
            <button class="btn-submit" type="submit">

                Create Task

            </button>

        </form>

    </div>

</div>

<style>

/* PAGE */

.task-page{
    padding:40px 20px;
    background:#f1f5f9;
    min-height:100vh;
    display:flex;
    justify-content:center;
}

/* CARD */

.task-card{
    width:100%;
    max-width:950px;
    background:white;
    border-radius:24px;
    padding:40px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
}

/* HEADER */

.task-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.task-header h1{
    margin:10px 0 5px;
    font-size:32px;
    color:#0f172a;
}

.task-header p{
    color:#64748b;
    margin:0;
}

.task-icon{
    width:70px;
    height:70px;
    background:#dbeafe;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
}

/* BACK BUTTON */

.back-btn{
    display:inline-block;
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
    margin-bottom:5px;
}

/* ALERTS */

.alert{
    padding:16px;
    border-radius:12px;
    margin-bottom:20px;
    font-weight:500;
    animation:fadeIn .3s ease;
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

.task-form{
    display:flex;
    flex-direction:column;
    gap:25px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group.full{
    grid-column:1 / -1;
}

.form-group label{
    margin-bottom:8px;
    font-weight:600;
    color:#334155;
}

.form-group input,
.form-group textarea,
.form-group select{
    width:100%;
    padding:14px;
    border:1px solid #cbd5e1;
    border-radius:14px;
    font-size:15px;
    background:white;
    transition:0.25s;
}

.form-group textarea{
    min-height:120px;
    resize:vertical;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.12);
}

/* BUTTON */

.btn-submit{
    background:#2563eb;
    color:white;
    border:none;
    padding:15px;
    border-radius:14px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:.25s;
}

.btn-submit:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
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

    .task-card{
        padding:25px;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

    .task-header{
        flex-direction:column;
        align-items:flex-start;
        gap:20px;
    }

}

</style>

<script>

setTimeout(() => {

    document.querySelectorAll('.alert').forEach(alert => {

        alert.style.transition = '0.4s';

        alert.style.opacity = '0';

        alert.style.transform = 'translateY(-5px)';

        setTimeout(() => {
            alert.remove();
        }, 400);

    });

}, 5000);

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>