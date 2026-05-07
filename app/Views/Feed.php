<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legacy - Feed</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="dark-theme">

    <header class="header-placement">
        <div class="header-left">
            <h1 class="logo">Legacy</h1>
        </div>
        
        <div class="header-middle">
            <div class="search-container">
                <form action="<?= site_url('feed') ?>" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Search users..." value="<?= esc($searchQuery) ?>" autocomplete="off">
                </form>

                <?php if (!empty($searchResults)): ?>
                    <div class="search-results-card card">
                        <div class="results-header">
                            <small>Search Results for "<?= esc($searchQuery) ?>"</small>
                            <a href="<?= site_url('feed') ?>" class="close-results">&times;</a>
                        </div>
                        <div class="results-list">
                            <?php foreach ($searchResults as $result): ?>
                                <a href="<?= site_url('profile/' . $result->id) ?>" class="result-item">
                                    <img src="<?= base_url('uploads/' . $result->profile_picture) ?>" class="avatar-xs">
                                    <div class="result-info">
                                        <strong><?= esc($result->username) ?></strong>
                                        <small><?= esc($result->first_name . ' ' . $result->last_name) ?></small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php elseif ($searchQuery !== ''): ?>
                    <div class="search-results-card card">
                        <div class="results-header">
                            <small>No users found matching "<?= esc($searchQuery) ?>"</small>
                            <a href="<?= site_url('feed') ?>" class="close-results">&times;</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="screen-wrapper">
        <aside class="column-left">
            <div class="profile-card">
                <a href="<?= site_url('profile') ?>">
                    <img src="<?= base_url('uploads/' . $user->profile_picture) ?>" class="profile-pic-large">
                </a>
                <h3 class="username"><?= esc($user->username) ?></h3>
                <div class="stats-row">
                    <span><strong><?= $followerCount ?></strong> Followers</span>
                    <span><strong><?= $followingCount ?></strong> Following</span>
                </div>
                <a href="<?= site_url('profile') ?>" class="btn-outline">View Profile</a>
            </div>
        </aside>

        <main class="column-center">
            <section class="post-creation-zone card">
                <form action="<?= site_url('post') ?>" method="post">
                    <?= csrf_field() ?>
                    <textarea name="content" required placeholder="What's on your mind?"></textarea>
                    <div class="action-row">
                        <button type="submit" class="btn-primary">Post</button>
                    </div>
                </form>
            </section>

            <section class="posts-display-zone">
                <?php foreach ($posts as $post): ?>
                    <div class="post-item card">
                        <div class="post-header">
                            <a href="<?= site_url('profile/' . $post->user_id) ?>">
                                <img src="<?= base_url('uploads/' . $post->profile_picture) ?>" class="avatar-sm">
                            </a>
                            <div class="post-meta">
                                <a href="<?= site_url('profile/' . $post->user_id) ?>" class="post-author-link">
                                    <strong><?= esc($post->username) ?></strong>
                                </a>
                                <small><?= date('M d, g:i a', strtotime($post->created_at)) ?></small>
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
                            <hr class="divider">
                            
                            <div class="comments-list">
                                <?php if (!empty($post->comments)): ?>
                                    <?php foreach ($post->comments as $comment): ?>
                                        <div class="comment-item">
                                            <img src="<?= base_url('uploads/' . $comment->profile_picture) ?>" class="avatar-xs">
                                            <div class="comment-bubble">
                                                <div class="comment-text">
                                                    <strong><?= esc($comment->username) ?></strong>
                                                    <span><?= esc($comment->content) ?></span>
                                                </div>
                                                <small class="comment-date"><?= date('M d, g:i a', strtotime($comment->created_at)) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <form action="<?= site_url('comment') ?>" method="post" class="comment-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="post_id" value="<?= $post->id ?>">
                                <img src="<?= base_url('uploads/' . $user->profile_picture) ?>" class="avatar-xs">
                                <div class="comment-input-group">
                                    <input type="text" name="comment" placeholder="Write a comment..." required autocomplete="off">
                                    <button type="submit" class="btn-send">➤</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>

        <aside class="column-right">
            <div class="sidebar-section card">
                <h3>Friends</h3>
                <p class="empty-state">No friends online</p>
            </div>

            <div class="sidebar-section card">
                <h3>Suggestions</h3>
                <p class="empty-state">People you may know</p>
            </div>
        </aside>
    </div>

</body>
</html>