<?php
/**
 * $Id: $
 */

/**
 * file-logger
 */
class mo_bonusbox__logger
{

  /**
   * log stack-trace & message
   * 
   * @param type $message 
   */
  public function logExecution($message = '')
  {
    if (!is_string($message))
    {
      $message = var_export($message, true);
    }
    if (!empty($message))
    {
      $message = ' ' . $message;
    }
    $backtrace = debug_backtrace();
    $caller = $backtrace[1];
    extract($caller);
    $this->message('CALL', "{$class}->{$function}$message ({$backtrace[0]['file']}:{$backtrace[0]['line']})");
  }

  /**
   * log info-level
   * 
   * @param type $message 
   */
  public function info($message)
  {
    $this->message('INFO', $message);
  }

  /**
   * log error level
   * 
   * @param string $message
   * @param type $infoObject 
   */
  public function error($message, $infoObject = null)
  {
    if ($infoObject)
    {
      $message .= "\n" . print_r($infoObject, true) . "\n" . str_repeat('-', 80) . "\n";
    }
    $this->message('ERROR', $message);
  }

  /**
   * write into file
   * 
   * @param type $type
   * @param type $message 
   */
  public function message($type, $message)
  {
    $logFile = oxConfig::getInstance()->getLogsDir() . 'mo_bonusbox.log';
    
    $this->logRotation($logFile);
    
    $date = date("Y-m-d H:i:s", time());

    $fh = fopen($logFile, "a");
    if ($fh)
    {
      $this->write($fh, "$date $type: $message \n");
      fclose($fh);
    }
  }
  
  /**
   * write into file (extracted for unit tests)
   * 
   * @param type $fh
   * @param type $string 
   */
  protected function write($fh, $string) {
    fwrite($fh, $string);
  }
  
  /**
   * backup logfiles (maybe implement "real" rotation later)
   * @param type $file 
   */
  protected function logRotation($file)
  {
    if($this->isRotationActive($file))
    {
      $backupLogFile = preg_replace('#^(.*)?\.([a-z]+)$#', '$1_' . time() . '_' . uniqid() . '.$2.zip', strtolower($file));
      
      $zip = new ZipArchive();
      $zip->open($backupLogFile, ZipArchive::CREATE);
      $zip->addFile($file, preg_replace('#^.*?/([^/]+)$#', '$1', $file));
      $zip->close();
      
      unlink($file);
    }
  }
  
  protected function isRotationActive($file)
  {
    return class_exists('ZipArchive') && filesize($file) > 10000000; //~10MB
  }

}