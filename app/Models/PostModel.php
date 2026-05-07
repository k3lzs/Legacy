<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    // Change post_id to content here:
    protected $allowedFields    = ['user_id', 'content']; 
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getPostsWithUsers()
{
    return $this->select('posts.*, users.username, users.profile_picture') // posts.* includes user_id
                ->join('users', 'users.id = posts.user_id')
                ->orderBy('posts.created_at', 'DESC')
                ->findAll();
}
}