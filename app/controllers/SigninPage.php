<?php

class SigninPage extends Controller
{
    protected $layout = 'signin-layout';
    
    public function displaySigninForm()
    {
        $this->renderView('signin-partial');
    }
    
    public function displaySignupForm()
    {
        $this->renderView('signup-partial');
    }
    
    public function displaySignupVerificationForm()
    {
        $this->renderView('signup-verification-partial');
    }
    
    public function signin()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        if (!$email) {
            $this->renderJson(['error' => 1, 'message' => 'Please enter your email address']);
        }
        if (!$password) {
            $this->renderJson(['error' => 1, 'message' => 'Please enter your password']);
        }
        $user = User::loadByEmail($email);
        if (!$user->userId || !password_verify($password, $user->password)) {
            $this->renderJson(['error' => 1, 'message' => 'Please make sure you have entered correct email and password']);
        }
        $user->saveToSession();
        $this->renderJson(['error' => 0]);
    }
    
    public function signout()
    {
        session_destroy();
        Route::redirect('/signin');
    }
    
    public function signup()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,}$/']
        ]);
        $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation');
        if (!$email) {
            $this->renderJson(['error' => 1, 'message' => 'Please enter a valid email address']);
        }
        if (!$password) {
            $this->renderJson(['error' => 1, 'message' => 'Password must contain minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character']);
        }
        if ($password != $passwordConfirmation) {
            $this->renderJson(['error' => 1, 'message' => 'Passwords do not match']);
        }
        $user = User::loadByEmail($email);
        if ($user->userId) {
            $this->renderJson(['error' => 1, 'message' => 'There is already a user with this email']);
        }
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->saveToSession();
        $this->sendVerificationMail($user->email);
        $this->renderJson(['error' => 0]);
    }
    
    public function verifySignup()
    {
        $code = filter_input(INPUT_POST, 'code', FILTER_VALIDATE_INT);
        $user = User::getConnectedUser();
        if (!$user->isValid()) {
            $this->renderJson(['error' => 1, 'message' => 'Sorry, something went wrong']);
        }
        if (time() > $this->getVerificationExpiry()) {
            $this->sendVerificationMail($user->email);
            $this->renderJson(['error' => 1, 'message' => 'Your code has expired, we have just sent you a new one']);
        }
        if (!$code) {
            $this->renderJson(['error' => 1, 'message' => 'Please enter the code']);
        }
        if ($code != $this->getVerificationCode()) {
            $this->renderJson(['error' => 1, 'message' => "The code you entered doesn't match"]);
        }
        $user->save();
        $user->saveToSession();
        $this->renderJson(['error' => 0, 'message' => 'Your account has been created']);
    }
    
    public function newVerification()
    {
        $_SESSION['verificationCode'] = mt_rand(10000, 99999);
        $_SESSION['verificationExpiry'] = time() + 60 * 3;
        return $_SESSION['verificationCode'];
    }
    
    public function getVerificationCode()
    {
        return $_SESSION['verificationCode'] ?? 0;
    }
    
    public function getVerificationExpiry()
    {
        return $_SESSION['verificationExpiry'] ?? 0;
    }
    
    public function sendVerificationMail($to)
    {
        if (env('APP_ENV') == 'prod') {
            $view = $this->getView('signup-verification-mail-template');
            $view->assign('code', $this->newVerification());
            mail($to, 'Your verification code', $view->fetch(), [
                'Content-Type' => 'text/html; charset=utf-8'
            ]);
        } else {
            error_log($this->newVerification());
        }
    }
}
