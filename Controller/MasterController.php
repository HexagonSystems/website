<?php
class MasterController
{
  
  // Sanitise infinite fields
  //$options = array("$email", "$fname", "$lname", "$dateJoined", "$address1", "$address2", "$suburb", "$state", "$postcode", "$password");
  //Note all these variables must be the same array length as the options variable.
  //$fieldName = array("email", "first name", "last name", "date joined", "address 1", "address 2", "suburb", "state", "postcode", "password");
  //$level = array("3", "2", "1", "2", "2", "1", "2", "2", "5", "4");
  //$charLength = array("0", "0", "0", "0", "0", "0", "0", "0", "4", "5");

// Sanitise infinite fields
//1 Just a message level
//2 Required field for a message level
//3 Required field and validate email/url
//4 Required field and check to see if the right char length (mainly used for passwords)
//If 4 is used then the Character Length variable must be inserted with the character length
//5 Required field and check to see if the right char length (more strict than 4, it must = to that char length).
//6 Required field for an enum check
function fieldSanitisation($userInput, $formOptions, $level, $charLength, $charType)
{
    //$userInput is whatever the user has entered intot he fields
    //$formOptions are the options so that it can give an error
    //$level is the message that way it knows which loop to fall into
    //$charLength is use for the maximum/minimum char length [0] is used to display the maximum always, [1] is used to display the minimum should it exist
    //$charType is the type of information it should contain eg: string/int/enum [0] contains either of the following:0 for is_string, 2 for is_float, 1 for is_numeric [1] Contains it in words for errors
    $tempOpNumber = count($userInput);
    $errors = "";
    for($tempLoopNumber = 0; $tempLoopNumber<$tempOpNumber; $tempLoopNumber++)
    {
        //This is used to sanitise a message field,
        //It isn't a required field,
        //It does allow for restrictions such as char length min and max and also type
        $inputLength = strlen($userInput[$tempLoopNumber]);
        if($level[$tempLoopNumber] == 1)
        {
            if($userInput[$tempLoopNumber] != "")
            {
                $userInput[$tempLoopNumber] = filter_var($userInput[$tempLoopNumber], FILTER_SANITIZE_STRING);
                //Check to see if it's the correct length, otherwise output error.
                if($charLength[0][$tempLoopNumber] == $charLength[1][$tempLoopNumber])
                {
                    if($inputLength == $charLength[0][$tempLoopNumber])
                    {
                        //It's ging to do nothing, its already been filtered, and if it falls into this then it is validated
                    }
                    else
                    {
                        $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Must contain a length of " . $charLength[0][$tempLoopNumber] . " " . $charType[1][$tempLoopNumber] . " values <br />";
                    }
                }
                elseif($inputLength < $charLength[0][$tempLoopNumber] && $inputLength > $charLength[1][$tempLoopNumber])
                {
                    if($charType[0] == 0)
                    {
                       if(is_string($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                    elseif($charType[0] == 1)
                    {
                       if(is_numeric($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                    elseif($charType[0] == 2)
                    {
                       if(is_float($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                }
                else
                {
                    //Display an error because it can't be validated.
                    $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Must contain a length of " . $charLength[1][$tempLoopNumber] . " - " . $charLength[0][$tempLoopNumber] . " " . $charType[1][$tempLoopNumber] . " values <br />";
                }
            }
        }
        //This is used to sanitise a message field,
        //This is a required field
        //It does allow for restrictions such as char length min and max and also type
        elseif($level[$tempLoopNumber] == 2)
        {
            if($userInput[$tempLoopNumber] != "")
            {
                $userInput[$tempLoopNumber] = filter_var($userInput[$tempLoopNumber], FILTER_SANITIZE_STRING);
                //Check to see if it's the correct length, otherwise output error.
                if($charLength[0][$tempLoopNumber] == $charLength[1][$tempLoopNumber])
                {
                    if($inputLength == $charLength[0][$tempLoopNumber])
                    {
                        //It's ging to do nothing, its already been filtered, and if it falls into this then it is validated
                    }
                    else
                    {
                        $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Must contain a length of " . $charLength[0][$tempLoopNumber] . " " . $charType[1][$tempLoopNumber] . " values <br />";
                    }
                }
                elseif($inputLength < $charLength[0][$tempLoopNumber] && $inputLength > $charLength[1][$tempLoopNumber])
                {

                    if($charType[0] == 0)
                    {
                       if(is_string($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                    elseif($charType[0] == 1)
                    {
                       if(is_numeric($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                    elseif($charType[0] == 2)
                    {
                       if(is_float($userInput[$tempLoopNumber]))
                       {
                           //It's going to do nothing, it's already been filtered, and if it falls into this then it is validated
                       }
                       else
                       {
                           $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Must only contain " . $charType[1][$tempLoopNumber] . " values <br />";
                       }
                    }
                }
                else
                {
                    //Display an error because it can't be validated
                    $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Must contain a length of " . $charLength[1][$tempLoopNumber] . " - " . $charLength[0][$tempLoopNumber] . " " . $charType[1][$tempLoopNumber] . " values <br />";
                }
            }
            else
            {
                //Display an error because it can't be validated
                $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:Please enter your " . $formOptions[$tempLoopNumber] . " <br />";
            }
        }
        //This is used to sanitise email or url field
        //This is a a required field
        //IF needed I will make another url field should it not be required
        elseif($level[$tempLoopNumber] == 3)
        {
            if($formOptions[$tempLoopNumber] == 'email')
            {
                if($userInput[$tempLoopNumber] != "")
                {
                    $userInput[$tempLoopNumber] = filter_var($userInput[$tempLoopNumber], FILTER_SANITIZE_EMAIL);
                    if (!filter_var($userInput[$tempLoopNumber], FILTER_VALIDATE_EMAIL))
                    {
                        $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:" . $userInput[$tempLoopNumber] . " is <strong>NOT</strong> a valid email address<br />";
                    }
                }
                else
                {

                    $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:Please enter your email address<br />";
                }
            }
            else //Will complete this later, not needed for now, for I wont be using the url statement
            {
                 if($userInput[$tempLoopNumber] != "")
                {
                    $userInput[$tempLoopNumber] = filter_var($userInput[$tempLoopNumber], FILTER_SANITIZE_URL);
                    if (!filter_var($userInput[$tempLoopNumber], FILTER_VALIDATE_URL))
                    {
                        $errors .= "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:" . $userInput[$tempLoopNumber] . " is <strong>NOT</strong> a valid url address<br />";
                    }
                }
                else
                {

                    $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:Please enter a url address<br />";
                }
            }
        }
        //This is only used if it's a required enum field.
        elseif($level[$tempLoopNumber] == 4)
        {
            if($userInput[$tempLoopNumber] != "")
            {
                $userInput[$tempLoopNumber] = filter_var($userInput[$tempLoopNumber], FILTER_SANITIZE_STRING);
                if($userInput[$tempLoopNumber] == "NONE")
                {
                    $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . ":Required Field:Please select a " . $formOptions[$tempLoopNumber] . "<br />";
                }
            }
            else
            {
                $errors .=  "Error:"  . $formOptions[$tempLoopNumber] . "Required Field:Please select a " . $formOptions[$tempLoopNumber] . "<br />";
            }
        }
    }
    //If there isn't an error return the field information sanitised and/or validated
    if($errors == "")
    {
        return $userInput;
    }
    //If there is an error return the errors
    else
    {
        //$errors[$tempFieldNumber] = 'e';
        return $errors;
    }
}
}
?>