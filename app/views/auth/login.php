<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>OVTMS Login</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#1e3a8a,#2563eb,#60a5fa);
}

.login-wrapper{
width:100%;
max-width:420px;
padding:20px;
}

.login-card{
background:#fff;
padding:45px;
border-radius:20px;
box-shadow:0 15px 40px rgba(0,0,0,.18);
}

.logo{
text-align:center;
font-size:32px;
font-weight:bold;
color:#2563eb;
margin-bottom:8px;
}

.subtitle{
text-align:center;
color:#6b7280;
margin-bottom:25px;
}

.error-message{
background:#f8d7da;
color:#721c24;
padding:12px;
border-radius:8px;
margin-bottom:15px;
}

.form-group{
margin-bottom:20px;
position:relative;
}

label{
display:block;
margin-bottom:6px;
font-weight:600;
color:#374151;
}

input{
width:100%;
padding:14px;
border:1px solid #d1d5db;
border-radius:10px;
font-size:15px;
outline:none;
transition:.3s;
}

input:focus{
border-color:#2563eb;
box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

.password-toggle{
position:absolute;
right:12px;
top:40px;
cursor:pointer;
}

button{
width:100%;
padding:15px;
border:none;
background:#2563eb;
color:#fff;
font-size:17px;
font-weight:bold;
border-radius:10px;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#1d4ed8;
transform:translateY(-2px);
}

</style>

</head>

<body>

<div class="login-wrapper">

<div class="login-card">

<div class="logo">
OVTMS
</div>

<div class="subtitle">
Office Visitor & Task Management System
</div>

<?php if(isset($error)): ?>

<div class="error-message">
<?= $error ?>
</div>

<?php endif; ?>

<!-- FIXED FORM -->
<form action="index.php?page=login" method="POST">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="Enter email"
required
>

</div>

<div class="form-group">

<label>Password</label>

<input
type="password"
id="password"
name="password"
placeholder="Enter password"
required
>

<span
class="password-toggle"
onclick="togglePassword()"
>

👁️

</span>

</div>

<button type="submit">

Login

</button>

</form>

</div>

</div>
<script>

function togglePassword() {

    let pass = document.getElementById("password");

    pass.type = (pass.type === "password") ? "text" : "password";
}

</script>



</body>
</html>