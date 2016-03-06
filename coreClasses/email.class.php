<?php

class email
{
    private $email;
    private $subject;
    private $notification;
    
    public function setEmail($val)
    {
        $this->email = $val;   
    }
    
    public function getEmail()
    {
        return $this->email;   
    }
    
    public function setSubject($val)
    {
        $this->subject = $val;   
    }
    
    public function getSubject()
    {
        return $this->subject;   
    }
    
    public function setNotification($val)
    {
        $this->notification = $val;   
    }
    
    public function getNotification()
    {
        return $this->notification;   
    }
    
    public function sendEmail ()
	{	
		if ( $_SERVER['HTTP_HOST'] != "localhost" )	{
			if ($this->getEmail()) {
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: Golf Pervs <gp@golfpervs.co.uk>' . "\r\n";

				mail ( $this->getEmail(), $this->getSubject(), $this->getNotification(), $headers );
			}
		}
	}
}

?>