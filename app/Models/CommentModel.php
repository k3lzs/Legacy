<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table            = 'comments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['post_id', 'user_id', 'content'];
    protected $returnType       = 'object';
    protected $useTimestamps    = true;

    /**
     * Get comments for a specific post with user details
     */
    public function getCommentsByPost($postId)
    {
        return $this->select('comments.*, users.username, users.profile_picture')
                    ->join('users', 'users.id = comments.user_id')
                    ->where('post_id', $postId)
                    ->orderBy('comments.created_at', 'ASC')
                    ->findAll();
    }
}