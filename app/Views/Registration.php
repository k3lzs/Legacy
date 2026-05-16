<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <div class="register-card">
        <h2>Create Account</h2>

        <?php if (isset($validation)) : ?>
            <div class="alert alert-error"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form action="<?= site_url('registration') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                    <small>At least 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="sex">Gender</label>
                    <select id="sex" name="sex" required>
                        <option value="">Choose one</option>
                        <option value="male" <?= old('sex') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= old('sex') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= old('sex') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?= old('birthdate') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?= old('address') ?>">
            </div>

            <button type="submit">Register</button>
        </form>

        <hr>
        <p class="login-link">Already have an account? <a href="<?= site_url('login') ?>">Login here</a></p>
    </div>
</body>
</html>
