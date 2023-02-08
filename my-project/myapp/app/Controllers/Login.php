<?php

namespace App\Controllers;

use App\Libraries\GithubOAuthClient;

/**
 * Class Login
 * 
 * Responsável pela autenticação de usuários via GitHub
 */
class Login extends BaseController
{
    private $git_client;

    public function __construct()
    {
        $this->git_client = new GithubOAuthClient(array(
            'client_id' => getenv('github_client_id'),
            'client_secret' => getenv('github_client_secret'),
            'redirect_uri' => getenv('github_redirect_uri')
        ));
    }

    /**
     * Tela de login
     * @return \Twig\Display
     */
    public function index()
    {
        $this->session->set('state', hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']));

        // Get the URL to authorize
        $auth_url = $this->git_client->getAuthorizeURL($this->session->get('state'));

        $data['link_auth'] = htmlspecialchars($auth_url);

        return $this->twig->display('auth/login', $data);
    }

    /**
     * Callback URL GitHub
     */
    public function callback()
    {
        $access_token = $this->git_client->getAccessToken($this->request->getGet('state'), $this->request->getGet('code'));

        $this->session->set('access_token', $access_token);
        
        return redirect()->route('login.auth');
    }

    public function auth()
    {
        if($this->session->get('access_token')) {
            // Get the user profile data from Github 
            $git_user = $this->git_client->getAuthenticatedUser($this->session->get('access_token'));

            $userModel = new \App\Models\UserModel();

            $data['oauth_uid'] = $git_user->id;
            $data['name'] = $git_user->name;
            $data['username'] = $git_user->login;
            $data['email'] = $git_user->email;
            $data['location'] = $git_user->location;
            $data['picture'] = $git_user->avatar_url;
            $data['link'] = $git_user->html_url;

            $user = $userModel->where('oauth_uid', $data['oauth_uid'])->first();
            if($user == null) {
                $userModel->insert($data);
            } else {
                $userModel
                    ->where('oauth_uid', $data['oauth_uid'])
                    ->set($data)
                    ->update();
            }

            $newdata = [
                'username' => $data['username'],
                'email' => $data['email'],
                'logged_in' => true,
            ];
            $this->session->set($newdata);

            return redirect()->route('patient');
        } else {
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to('/');
    }
}
