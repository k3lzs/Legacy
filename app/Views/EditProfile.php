<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <header>
        <h1>Social Media</h1>
        <nav>
            <a href="<?= site_url('profile') ?>">Back to Profile</a>
            <a href="<?= site_url('logout') ?>">Logout</a>
        </nav>
    </header>

    <div>
        <div>
            <h1>Edit Profile</h1>

            <!-- Messages -->
            <?php if (session()->getFlashdata('success')) : ?>
                <div><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (isset($validation)) : ?>
                <div><?= $validation->listErrors() ?></div>
            <?php endif; ?>

            <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= esc(old('username', $user->username)) ?>" required>
                </div>

                <div>
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?= esc(old('first_name', $user->first_name)) ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?= esc(old('last_name', $user->last_name)) ?>" required>
                </div>

                <div class="form-group">
                    <label for="sex">Gender:</label>
                    <select id="sex" name="sex" required>
                        <option value="male" <?= old('sex', $user->sex) === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= old('sex', $user->sex) === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= old('sex', $user->sex) === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">Birthdate:</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?= esc(old('birthdate', $user->date_of_birth)) ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address"><?= esc(old('address', $user->address)) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current Profile Picture:</label>
                    <div>
                        <img src="<?= base_url('uploads/' . $user->profile_picture) ?>" alt="Current Profile Picture">
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Upload New Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    <small>Max 2MB. Allowed: JPG, PNG, GIF</small>
                </div>

                <button type="submit">Save Changes</button>
                <a href="<?= site_url('profile') ?>">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
