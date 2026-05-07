<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\FollowModel;
use App\Models\CommentModel;

class FeedController extends BaseController
{
    public function index()
{
    $userId = session()->get('user_id');
    if (!$userId) return redirect()->to('/login');

    $userModel    = new UserModel();
    $postModel    = new PostModel();
    $followModel  = new FollowModel();
    $commentModel = new CommentModel();

    $userData = $userModel->find($userId);
    $posts    = $postModel->getPostsWithUsers();

    // Attach comments to each post
    foreach ($posts as $post) {
        $post->comments = $commentModel->getCommentsByPost($post->id);
    }

    $searchQuery = $this->request->getGet('q') ?? '';
    
    return view('feed', [
        'user'           => (object) $userData,
        'posts'          => $posts,
        'followerCount'  => $followModel->getFollowerCount($userId),
        'followingCount' => $followModel->getFollowingCount($userId),
        'searchQuery'    => $searchQuery,
        'searchResults'  => !empty($searchQuery) ? $userModel->searchUsers($searchQuery) : []
    ]);
}

public function addComment()
{
    if (!session()->get('logged_in')) return redirect()->to('/login');

    $postId  = $this->request->getPost('post_id');
    $content = trim($this->request->getPost('comment'));

    if (!empty($content)) {
        $commentModel = new CommentModel();
        $commentModel->insert([
            'post_id' => $postId,
            'user_id' => session()->get('user_id'),
            'content' => $content
        ]);
    }

    return redirect()->back();
}

    public function createPost()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $content = trim($this->request->getPost('content'));
        if (empty($content) || strlen($content) > 500) {
            return redirect()->back()->with('error', 'Invalid post content.');
        }

        $postModel = new PostModel();
        $postModel->insert([
            'user_id' => session()->get('user_id'),
            'content' => $content,
        ]);

        return redirect()->to('/feed')->with('success', 'Post created!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}