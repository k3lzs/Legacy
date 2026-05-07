<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    
    // ADD THIS LINE BELOW
    protected $returnType       = 'object'; 

    protected $allowedFields    = [
        'first_name', 'last_name', 'username', 'email', 
        'password_hash', 'sex', 'date_of_birth', 
        'address', 'profile_picture'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Find a user by email for login purposes
     */
    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Search users for the feed search bar
     */
    public function searchUsers($query)
    {
        return $this->like('username', $query)
                    ->orLike('first_name', $query)
                    ->findAll();
    }
}