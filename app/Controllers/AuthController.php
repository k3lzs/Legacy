<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function login()
    {
        return view('Login');
    }

    public function loginPost()
    {
        $userModel = new UserModel();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Use the model to find the user
        $user = $userModel->getUserByEmail($email);

        // CodeIgniter Models return an array by default, 
        // access values accordingly or set returnType to 'object' in the Model.
        // Change this line:
if ($user && password_verify($password, $user->password_hash)) {
    session()->set([
        'user_id'   => $user->id,
        'username'  => $user->username,
        'email'     => $user->email,
        'logged_in' => true,
    ]);
    return redirect()->to('/feed');
}

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    public function registration()
    {
        return view('Registration');
    }

    public function registrationPost()
    {
        $rules = [
            'first_name'       => 'required|min_length[2]|max_length[50]',
            'last_name'        => 'required|min_length[2]|max_length[50]',
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'sex'              => 'required|in_list[male,female,other]',
            'birthdate'        => 'required|valid_date[Y-m-d]',
            'address'          => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return view('Registration', [
                'validation' => $this->validator,
            ]);
        }

        $userModel = new UserModel();

        $data = [
            'first_name'      => $this->request->getPost('first_name'),
            'last_name'       => $this->request->getPost('last_name'),
            'username'        => $this->request->getPost('username'),
            'email'           => $this->request->getPost('email'),
            'password_hash'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'sex'             => $this->request->getPost('sex'),
            'date_of_birth'   => $this->request->getPost('birthdate'),
            'address'         => $this->request->getPost('address') ?: null,
            'profile_picture' => 'default.png',
        ];

        // The model handles the insert and timestamps automatically
        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Registration successful! Please log in.');
    }
}