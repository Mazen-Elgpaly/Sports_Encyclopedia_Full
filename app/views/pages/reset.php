
<style>
.container {
  height: 100vh;
  width: 100vw;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 10px;
}

</style>
    <div class="login-card">
        <h1 class="headerTitle">Reset Your Account</h1>
        <p class="bodyTitle">Help us to restore your account.</p>
        <p class="bodyTitle">We will send to your account OTP-code.</p>
        <label class="label">
            <p class="inputTitle">Email Address</p>
            <span class="icon">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email" class="input" placeholder="Enter your email address" autocomplete="off" />
        </label>
        <a  href="<?= BASE_URL ?>/otp" class="login-button">Send</a>
    </div>

