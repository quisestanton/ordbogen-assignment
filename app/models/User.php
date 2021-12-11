<?php

class User extends Model
{
    public $userId = 0;
    public $email = '';
    public $password = '';
    
    protected $table = 'users';
    protected $primaryKeys = ['userId'];
    
    public static function loadByEmail($email)
    {
        $user = self::where('email', $email)->asObject(get_class())->first();
        return $user ?: new self();
    }
    
    public static function getConnectedUser()
    {
        return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : new self();
    }
    
    public function saveToSession()
    {
        $_SESSION['user'] = serialize($this);
    }
    
    public function isValid()
    {
        return $this->email && $this->password;
    }
}