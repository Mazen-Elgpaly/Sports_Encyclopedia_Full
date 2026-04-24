<style>
    body{
            flex-direction: row;
    }
    video{
        transform: translateY(-60px);
        min-width: 900px;
        width: 50%;
    }
    .bar {
  height: 100%;
  width: 0%;
  background: #e74c3c;
  transition: width 0.3s ease, background-color 0.3s ease;
}
.meter {
  width: 100%;
  height: 8px;
  background-color: var(--bg-color);
  border-radius: 4px;
  margin-top: 10px;
  overflow: hidden;
}
.retePass {
  width: 75%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
}


</style>
<video autoplay loop muted playsinline>
    <source src="<?= BASE_URL ?>/images\0001-0032.webm" type="video/webm">
</video>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Create Your Account</h1>
        </div>

        <?php if ($error): ?>
            <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:.9rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="<?= BASE_URL ?>/register">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       placeholder="Mohamed Ali" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password"
                       placeholder="At least 6 characters" required>
                <!-- Password strength meter (JS/password.js) -->
                <div class="retePass">
                    <div class="meter"><div class="bar" id="bar"></div></div>
                    <small class="text" id="strength"></small>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password"
                       placeholder="Repeat password" required>
            </div>

            <button type="submit" class="login-button">Create Account</button>

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
                <p>Already have an account? <a href="<?= BASE_URL ?>/login" class="register-link">Sign in</a></p>
            </div>
        </form>
    </div>
</div>
<script src="<?= BASE_URL ?>/js/password.js"></script>
