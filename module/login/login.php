<?php
session_start();

$message = "";
$messageCode = "";

if (isset($_GET['code']) && isset($_GET['message'])) {
    $messageCode = $_GET['code'];
    $message = $_GET['message'];
}

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UIA - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/normalize-5.0.0.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-brand">
            <img src="../../asset/img/logo-uia.png" alt="UIA" onerror="this.style.display='none'">
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo htmlspecialchars($messageCode); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="tab-group">
            <button type="button" class="tab-btn" data-target="signup">Sign Up</button>
            <button type="button" class="tab-btn active" data-target="login">Log In</button>
        </div>

        <div class="tab-panel active" id="login">
            <form action="authenticate.php" method="post" class="auth-form">
                <div class="field-wrap">
                    <label for="login-username">Email Address</label>
                    <div class="input-icon">
                        <i class='bx bx-envelope'></i>
                        <input id="login-username" type="email" name="username" placeholder="you@example.com" required autocomplete="off"/>
                    </div>
                </div>

                <div class="field-wrap">
                    <label for="login-password">Password</label>
                    <div class="input-icon">
                        <i class='bx bx-lock-alt'></i>
                        <input id="login-password" type="password" name="password" placeholder="••••••••" required autocomplete="off"/>
                    </div>
                </div>

                <p class="forgot"><a href="#">Forgot Password?</a></p>

                <button type="submit" class="button button-block">Log In</button>
            </form>
        </div>

        <div class="tab-panel" id="signup">
            <form action="register.php" method="post" class="auth-form" id="signup-form">
                <div class="top-row">
                    <div class="field-wrap">
                        <label for="su-firstname">First Name</label>
                        <input id="su-firstname" type="text" name="firstname" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        <label for="su-lastname">Last Name</label>
                        <input id="su-lastname" type="text" name="lastname" required autocomplete="off"/>
                    </div>
                </div>

                <div class="top-row">
                    <div class="field-wrap">
                        <label for="su-birthday">Birthday</label>
                        <input id="su-birthday" type="date" name="birthday" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        <label for="su-gender">Gender</label>
                        <select id="su-gender" name="gender" required autocomplete="off">
                            <option value="" disabled selected></option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>

                <div class="top-row">
                    <div class="field-wrap">
                        <label for="su-phone">Phone Number</label>
                        <input id="su-phone" type="tel" name="phone" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        <label for="su-email">Email Address</label>
                        <input id="su-email" type="email" name="email" required autocomplete="off"/>
                    </div>
                </div>

                <div class="top-row">
                    <div class="field-wrap">
                        <label for="su-password">Password</label>
                        <input id="su-password" type="password" name="password" required autocomplete="off"/>
                    </div>
                    <div class="field-wrap">
                        <label for="su-password2">Re-type Password</label>
                        <input id="su-password2" type="password" required autocomplete="off"/>
                    </div>
                </div>

                <p class="field-error" id="signup-error"></p>

                <button type="submit" class="button button-block">Submit</button>
            </form>
        </div>

    </div>
</div>

<script type="text/javascript">
    // Tab switching (plain JS, no jQuery needed)
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabButtons.forEach(function (b) { b.classList.remove('active'); });
            tabPanels.forEach(function (p) { p.classList.remove('active'); });

            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });

    // Simple client-side password confirmation check for sign up
    document.getElementById('signup-form').addEventListener('submit', function (e) {
        const pass = document.getElementById('su-password').value;
        const pass2 = document.getElementById('su-password2').value;
        const errorEl = document.getElementById('signup-error');

        if (pass !== pass2) {
            e.preventDefault();
            errorEl.textContent = 'Password confirmation does not match.';
            errorEl.style.display = 'block';
        } else {
            errorEl.style.display = 'none';
        }
    });

    setTimeout(function () {
        const msg = document.querySelector('.message');
        if (msg) {
            msg.style.transition = 'opacity 1s';
            msg.style.opacity = 0;
            setTimeout(function () { msg.style.display = 'none'; }, 1000);
        }
    }, 4000);
</script>
</body>
</html>