<?php

class Dashboard extends Controller
{
    protected $layout = 'basic-layout';
    
    public function displayPage()
    {
        if (!User::getConnectedUser()->userId) {
            Route::redirect('/signin');
        }
        parent::displayPage();
    }
}