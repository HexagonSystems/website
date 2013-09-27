<?php

class Mail
{

    private $database = FALSE;
    private $email = array(
            "receiver"	=>	FALSE,
            "subject"	=>	FALSE,
            "message" 	=>	FALSE,
            "sender"	=>	FALSE,
    );

    public function __construct()
    {

    } //end constructor

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    public function sendMail()
    {
        foreach ($this->email as $value) {
            if (!$value) {
                return false;
            }
        }
        try {
            mail($this->email['receiver'],$this->email['subject'],$this->email['message'],"From: ".$this->email['sender']);

            return true;
        } catch (Exception $e) {
            echo $e;

            return false;
        }

    }

    public function addReceiver($email)
    {
        if ($this->email['receiver']) {
            $this->email['receiver'] .= ", ".$email;
        } else {
            $this->email['receiver'] = $email;
        }
    }

    public function setSubject($subject)
    {
        $this->email['subject'] = $subject;
    }

    public function setMessage($message)
    {
        $this->email['message'] = $message;
    }

    public function setSender($sender)
    {
        $this->email['sender'] = $sender;
    }

    public function setHeader($header)
    {
        $this->email['header'] = $header;
    }
}
