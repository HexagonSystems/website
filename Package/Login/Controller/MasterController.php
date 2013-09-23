<?php
class MasterController
{
  // Sanitise infinite fields
  //$options = array("$email", "$fname", "$lname", "$dateJoined", "$address1", "$address2", "$suburb", "$state", "$postcode", "$password");
  //Note all these variables must be the same array length as the options variable.
  //$fieldName = array("email", "first name", "last name", "date joined", "address 1", "address 2", "suburb", "state", "postcode", "password");
  //$level = array("3", "2", "1", "2", "2", "1", "2", "2", "5", "4");
  //$charLength = array("0", "0", "0", "0", "0", "0", "0", "0", "4", "5");
  
  //The different levels
  //1 Just a message level
  //2 Required field for a message level
  //3 Required field and validate
  //4 Required field and check to see if the right char length (mainly used for passwords)
  //If 4 is used then the Character Length variable must be inserted with the character length
  //5 Required field and check to see if the right char length (more strict than 4, it must = to that char length).
  public function fieldSanitisation($field, $fieldName, $level, $charLength)
  {
      $tempFieldNumber = count($field);
      $errors = '';
      for($tempLoopNumber = 0; $tempLoopNumber<$tempFieldNumber; $tempLoopNumber++)
      {
          //This is just to sanatise a message field
          if($level[$tempLoopNumber] == 1)
          {
              $field[$tempLoopNumber] = filter_var($field[$tempLoopNumber], FILTER_SANITIZE_STRING);
          }
          // This field is for sanitising a required field
          elseif($level[$tempLoopNumber] == 2)
          {
              if($field[$tempLoopNumber] != "")
              {
                  $field[$tempLoopNumber] = filter_var($field[$tempLoopNumber], FILTER_SANITIZE_STRING);
                  if($field[$tempLoopNumber] == "")
                  {
                      $errors .= 'Please enter a ' . $fieldName[$tempLoopNumber] . ' <br />';
                  }
              }
              else
              {
                  $errors .= 'Please enter a ' . $fieldName[$tempLoopNumber] . ' <br />';
              }
          }
          //This field is only for fields that can be validated via the php validation inbuilt commands
          //Email and URL's at the moment are the only ones that I know of
          elseif($level[$tempLoopNumber] == 3)
          {
              if($fieldName[$tempLoopNumber] == 'email')
              {
                  if ($field[$tempLoopNumber] != "")
                  {
                      $field[$tempLoopNumber] = filter_var($field[$tempLoopNumber], FILTER_SANITIZE_EMAIL);
                      if (!filter_var($field[$tempLoopNumber], FILTER_VALIDATE_EMAIL))
                      {
                          $errors .= $field[$tempLoopNumber] . ' is <strong>NOT</strong> a valid email address<br />';
                      }
                  }
                  else
                  {
                      $errors .= 'Please enter your email address <br />';
                  }
              }
              else //Will complete this later, not needed for now, for I wont be using the url statement
              {
  
              }
          }
          //This will check the length of the string to ensure that it is valid, as well as sanitising it.
          elseif($level[$tempLoopNumber] == 4)
          {
              if($field[$tempLoopNumber] != "")
              {
                  $field[$tempLoopNumber] = filter_var($field[$tempLoopNumber], FILTER_SANITIZE_STRING);
                  if(strlen($field[$tempLoopNumber]) < $charLength[$tempLoopNumber])
                  {
                      $errors .= 'Please enter a valid ' . $fieldName[$tempLoopNumber] . '<br /> It must contain atleast ' . $charLength[$tempLoopNumber] . ' characters <br />';
                  }
              }
              else
              {
                  $errors .= 'Please enter a ' . $fieldName[$tempLoopNumber]  . ' to send <br />';
              }
          }
          //This will check the length of the string, making sure that it is = to the amount entered to ensure that it is valid, as well as sanitising it.
          elseif($level[$tempLoopNumber] == 5)
          {
              if($field[$tempLoopNumber] != "")
              {
                  $field[$tempLoopNumber] = filter_var($field[$tempLoopNumber], FILTER_SANITIZE_STRING);
                  if(strlen($field[$tempLoopNumber]) != $charLength[$tempLoopNumber])
                  {
                      $errors .= 'Please enter a valid ' . $fieldName[$tempLoopNumber] . '<br /> It must contain only ' . $charLength[$tempLoopNumber] . ' numbers <br />';
                  }
              }
              else
              {
                  $errors .= 'Please enter a ' . $fieldName[$tempLoopNumber]  . ' to send. <br />';
              }
          }
      }
      //If there isn't an error return the field information sanitised and/or validated
      if($errors == '')
      {
          return $field;
      }
      //If there is an error return the errors
      else
      {
          $errors[$tempFieldNumber] = 'e';
          return $errors;
      }
  }
}
?>