<style>
    body{
            flex-direction: row;
    }
    video{
        transform: translateY(-60px);
        min-width: 900px;
        width: 50%;
    }


</style>
<video autoplay loop muted playsinline>
    <source src="<?= BASE_URL ?>/images\0001-0032.webm" type="video/webm">
</video>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Log In to Your Account</h1>
        </div>

        <?php if ($error): ?>
            <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:.9rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="<?= BASE_URL ?>/login">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="Enter your email address" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password"
                       placeholder="Enter your password" required>
            </div>

            <div class="form-row">
                <label class="checkbox-wrapper">
                    <input type="checkbox" id="remember_me" name="remember_me" value="1">
                    <span>Remember Me</span>
                </label>
                <a  href="<?= BASE_URL ?>/reset" class="forgot-link">Forgot Password?</a>

            </div>

            <button type="submit" class="login-button">Login</button>

            <div class="social-login">

    <a href="https://accounts.google.com/signin" class="social-btn google">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg">
        <span>Continue with Google</span>
    </a>
<div class="divider">
    <span>OR</span>
</div>
    <a href="https://www.facebook.com/login" class="social-btn facebook">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Facebook_Logo_2023.png">
        <span>Continue with Facebook</span>
    </a>
<div class="divider">
    <span>OR</span>
</div>
    <a href="https://twitter.com/login" class="social-btn twitter">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/twitter/twitter-original.svg">
        <span>Continue with X (Twitter)</span>
    </a>
<div class="divider">
    <span>OR</span>
</div>
    <a href="https://github.com/login" class="social-btn github">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg">
        <span>Continue with GitHub</span>
    </a>

</div>

            <div class="register-text">
                <p>Don't have an account? <a href="<?= BASE_URL ?>/register" class="register-link">Register</a></p>
            </div>
        </form>
    </div>
</div>
