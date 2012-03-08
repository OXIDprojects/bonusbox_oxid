<?php

/**
 * $Id: $
 */
class mo_bonusbox__exception__feedback_error extends Exception
{
  protected $errorResponse = array();
  
  public function setErrorResponse($errorResponse)
  {
    $this->errorResponse = $errorResponse;
  }
  
  public function __toString()
  {
    return 'Bonusbox Feedback-Error "' . $this->errorResponse['type'] . '": ' . $this->errorResponse['message'];
  }
}