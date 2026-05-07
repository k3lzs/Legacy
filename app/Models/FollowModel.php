<?php

namespace App\Models;

use CodeIgniter\Model;

class FollowModel extends Model
{
    protected $table = 'follows';
    protected $primaryKey = 'id';
    protected $allowedFields = ['follower_id', 'followed_id']; // Required for following
    protected $returnType = 'object';

    public function getFollowerCount($userId)
    {
        return $this->where('followed_id', $userId)->countAllResults();
    }

    public function getFollowingCount($userId)
    {
        return $this->where('follower_id', $userId)->countAllResults();
    }

    // Add this helper for your ProfileController index
    public function isFollowing($followerId, $followedId)
    {
        return $this->where('follower_id', $followerId)
                    ->where('followed_id', $followedId)
                    ->countAllResults() > 0;
    }
}