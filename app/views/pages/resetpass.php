
    <div class="login-card">
        <h1 class="headerTitle">Reset Your Account</h1>
        <p class="bodyTitle">Thanks For join our Website.</p>
        <label class="label">
            <p class="inputTitle">New Password</p>
            <span class="icon">
                <i class="bi bi-eye"></i>
            </span>
            <input id="password" type="password" class="input" placeholder="Enter your password" autocomplete="off" />
        </label>
        <div class="retePass">
            <div class="meter">
                <div class="bar" id="bar"></div>
            </div>
            <div class="text" id="strength"></div>
        </div>
        <label class="label">
            <p class="inputTitle">Confirm Password</p>
            <span class="icon">
                <i class="bi bi-eye"></i>
            </span>
            <input type="password" class="input" placeholder="Confirm your password" autocomplete="off" />
        </label>
        <a  href="<?= BASE_URL ?>/login" class="login-button">Reset</a>

    </div>