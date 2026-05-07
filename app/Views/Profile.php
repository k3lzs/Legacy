<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($user->username) ?> - Legacy</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="dark-theme">

    <header class="header-placement">
        <div class="header-left">
            <h1 class="logo">Legacy</h1>
        </div>
        <div class="header-right">
            <nav class="icon-nav">
                <a href="<?= site_url('feed') ?>">Home</a>
                <a href="<?= site_url('logout') ?>">Logout</a>
            </nav>
        </div>
    </header>

    <div class="screen-wrapper">
        <!-- Column 1: Profile Details -->
        <aside class="column-left">
            <div class="profile-card card">
                <img src="<?= base_url('uploads/' . $user->profile_picture) ?>" class="profile-pic-large">
                <h2 class="username"><?= esc($user->username) ?></h2>
                <p class="full-name"><?= esc($user->first_name . ' ' . $user->last_name) ?></p>

                <div class="stats-row">
                    <div class="stat-item">
                        <strong><?= count($posts) ?></strong>
                        <small>Posts</small>
                    </div>
                    <div class="stat-item">
                        <strong><?= $followerCount ?? 0 ?></strong>
                        <small>Followers</small>
                    </div>
                    <div class="stat-item">
                        <strong><?= $followingCount ?? 0 ?></strong>
                        <small>Following</small>
                    </div>
                </div>

                <?php if (isset($isCurrentUser) && $isCurrentUser) : ?>
                    <a href="<?= site_url('profile/edit') ?>" class="btn-primary" style="text-decoration: none; display: block; text-align: center;">Edit Profile</a>
                <?php elseif (isset($isFollowing)) : ?>
                    <form action="<?= site_url(($isFollowing ? 'unfollow/' : 'follow/') . $user->id) ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="<?= $isFollowing ? 'btn-outline' : 'btn-primary' ?> w-100">
                            <?= $isFollowing ? 'Unfollow' : 'Follow' ?>
                        </button>
                    </form>
                <?php endif; ?>

                <div class="detailed-info">
                    <div class="info-group">
                        <label>Email</label>
                        <p><?= esc($user->email) ?></p>
                    </div>
                    <div class="info-group">
                        <label>Gender</label>
                        <p><?= esc($user->sex) ?></p>
                    </div>
                    <div class="info-group">
                        <label>Birthdate</label>
                        <p><?= esc($user->date_of_birth) ?></p>
                    </div>
                    <div class="info-group">
                        <label>Address</label>
                        <p><?= esc($user->address ?: 'Not provided') ?></p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Column 2: User's Posts -->
       <main class="column-center">
    <div class="section-title">
        <h3><?= $isCurrentUser ? 'Your Posts' : esc($user->username) . "'s Posts" ?></h3>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <section class="posts-display-zone">
        <?php if (empty($posts)) : ?>
            <div class="card empty-state">No posts yet.</div>
        <?php else : ?>
            <?php foreach ($posts as $post) : ?>
                <div class="post-item card">
                    <div class="post-header">
                        <img src="<?= base_url('uploads/' . ($post->profile_picture ?: 'default.png')) ?>" class="avatar-sm">
                        <div class="post-meta">
                            <strong><?= esc($post->username) ?></strong>
                            <small><?= date('M d, Y H:i', strtotime($post->created_at)) ?></small>
                        </div>
                    </div>
                    
                    <div class="post-body">
                        <p><?= esc($post->content) ?></p>
                    </div>

                    <div class="post-footer">
                        <button class="btn-social">👍 Like</button>
                        <button class="btn-social">💬 Comment</button>
                    </div>

                    <div class="comment-section">
                        <div class="comments-list">
                            <?php if (!empty($post->comments)) : ?>
                                <?php foreach ($post->comments as $comment) : ?>
                                    <div class="comment-item">
                                        <img src="<?= base_url('uploads/' . ($comment->profile_picture ?: 'default.png')) ?>" class="avatar-xs">
                                        <div class="comment-bubble">
                                            <div class="comment-text">
                                                <strong><?= esc($comment->username) ?></strong>
                                                <span><?= esc($comment->content) ?></span>
                                            </div>
                                            <small class="comment-date"><?= date('M d, H:i', strtotime($comment->created_at)) ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <form action="<?= site_url('comment') ?>" method="post" class="comment-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="post_id" value="<?= $post->id ?>">
                            
                            <img src="<?= base_url('uploads/' . (session()->get('user_profile_picture') ?: 'default.png')) ?>" class="avatar-xs">
                            
                            <div class="comment-input-group">
                                <input type="text" name="comment" placeholder="Write a comment..." required autocomplete="off">
                                <button type="submit" class="btn-send">➤</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

        <!-- Column 3: Right Sidebar (Optional Info) -->
        <aside class="column-right">
             <div class="sidebar-section card">
                <h3>Navigation</h3>
                <a href="<?= site_url('feed') ?>" class="btn-social">← Back to Feed</a>
            </div>
        </aside>
    </div>

</body>
</html>