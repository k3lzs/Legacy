<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\FollowModel;
use App\Models\CommentModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ProfileController extends BaseController
{
    // We instantiate models in the methods or constructor for better performance
    protected $userModel;
    protected $postModel;
    protected $followModel;
    protected $commentModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->userModel    = new UserModel();
        $this->postModel    = new PostModel();
        $this->followModel  = new FollowModel();
        $this->commentModel = new CommentModel();
    }

    /**
     * Display the profile (Main Entry)
     */
    public function index($id = null)
    {
        $session = session();
        $loggedInUserId = $session->get('user_id');

        if (!$loggedInUserId) {
            return redirect()->to('/login');
        }

        // Determine target user
        $targetUserId = ($id === null) ? $loggedInUserId : $id;
        $user = $this->userModel->find($targetUserId);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User not found.");
        }

        return $this->_renderProfile($user, $loggedInUserId);
    }

    /**
     * View profile by username
     */
    public function view($username)
    {
        $session = session();
        $loggedInUserId = $session->get('user_id');

        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->to('/feed')->with('error', 'User not found.');
        }

        return $this->_renderProfile($user, $loggedInUserId);
    }

    /**
     * Helper to avoid repeating logic between index() and view()
     */
    private function _renderProfile($user, $loggedInUserId)
    {
        $isCurrentUser = ($user->id == $loggedInUserId);

        // Fetch posts and attach comments to each
        $posts = $this->postModel->where('user_id', $user->id)
                                 ->orderBy('created_at', 'DESC')
                                 ->findAll();

        foreach ($posts as $post) {
            $post->username = $user->username;
            $post->profile_picture = $user->profile_picture;
            $post->comments = $this->commentModel->getCommentsByPost($post->id);
        }

        return view('Profile', [
            'user'           => $user,
            'posts'          => $posts,
            'isCurrentUser'  => $isCurrentUser,
            'isFollowing'    => $this->followModel->isFollowing($loggedInUserId, $user->id),
            'followerCount'  => $this->followModel->getFollowerCount($user->id),
            'followingCount' => $this->followModel->getFollowingCount($user->id),
        ]);
    }

    /**
     * Handle profile updates
     */
    public function update()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        // Validation rules (using is_unique ignore syntax for the current user)
        $rules = [
            'username'   => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$userId}]",
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'sex'        => 'required|in_list[male,female,other]',
            'birthdate'  => 'required|valid_date[Y-m-d]',
            'address'    => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return view('EditProfile', [
                'user'       => $user,
                'validation' => $this->validator,
            ]);
        }

        $updateData = [
            'username'      => $this->request->getPost('username'),
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'sex'           => $this->request->getPost('sex'),
            'date_of_birth' => $this->request->getPost('birthdate'),
            'address'       => $this->request->getPost('address') ?: null,
        ];

        // Handle File Upload
        $file = $this->request->getFile('profile_picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/', $newName);
            $updateData['profile_picture'] = $newName;
        }

        $this->userModel->update($userId, $updateData);

        return redirect()->to('/profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Follow logic
     */
    public function follow($userId)
    {
        $currentId = session()->get('user_id');
        if (!$currentId || $currentId == $userId) return redirect()->back();

        if (!$this->followModel->isFollowing($currentId, $userId)) {
            $this->followModel->insert([
                'follower_id' => $currentId,
                'followed_id' => $userId
            ]);
        }

        return redirect()->back()->with('success', 'Followed!');
    }

    /**
     * Unfollow logic
     */
    public function unfollow($userId)
    {
        $currentId = session()->get('user_id');
        
        $this->followModel->where('follower_id', $currentId)
                          ->where('followed_id', $userId)
                          ->delete();

        return redirect()->back()->with('success', 'Unfollowed.');
    }
}