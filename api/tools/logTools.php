<?php
/**
 * ===========================================================================
 * Log tracing.
 * Not to be called directly. Use following functions instead.
 *
 * @param  $message message
 *          to store on log
 * @param  $level level
 *          of trace : 1=error, 2=trace, 3=debug, 4=script
 * @return void
 */
function logTracing($message, $level=9, $increment=0)
{
    $execTime="";
    $logLevel=Parameter::getGlobalParameter('logLevel');
    $tabcar='                        ';
    if ($logLevel==5) {
        if ($level<=3) { echo $message;
        }
        return;
    }
    $logFile=Parameter::getGlobalParameter('logFile');
    if (!$logFile or $logFile=='' or $level==9) {
        exit();
    }
    if ($level<=$logLevel) {
        $file=str_replace('${date}', date('Ymd'), $logFile);

        if (is_array($message) or is_object($message)) {
            $tab=($increment==0)?'':substr($tabcar, 0, ($increment*3-1));
            $txt=$tab.(is_array($message)?'Array['.count($message).']':'Object['.get_class($message).']');
            logTracing($txt, $level, $increment);

            foreach ($message as $ind=>$val) {
                $tab=substr($tabcar, 0, (($increment+1)*3-1));
                if (is_array($val) or is_object($val)) {
                    $txt=$tab.$ind.' => ';
                    $txt.=is_array($val)?'Array ':'Object ';
                    logTracing($txt, $level, $increment+1);
                    logTracing($val, $level, $increment+1);
                } else {
                    $txt=$tab.$ind.' => '.$val;
                    logTracing($txt, $level, $increment+1);
                }
            }

            $level=999;
            $msg='';

        } else {
            $msg=$message."\n";

        }
 
        
        switch ($level) {
        case 1 :
            $msg=date('Y-m-d H:i:s').substr(microtime(), 1, 4).$execTime." ***** ERROR ***** ".$msg;
            break;
        case 2 :
            $msg=date('Y-m-d H:i:s').substr(microtime(), 1, 4).$execTime." ===== TRACE ===== ".$msg;
            break;
        case 3 :
            $msg=date('Y-m-d H:i:s').substr(microtime(), 1, 4).$execTime." ----- DEBUG ----- ".$msg;
            break;
        case 4 :
            $msg=date('Y-m-d H:i:s').substr(microtime(), 1, 4).$execTime." ..... SCRIPT .... ".$msg;
            break;
        default :
            break;
        }
        
        $dir=dirname($file);
        if (!file_exists($dir)) {
            echo '<br/><span class="messageERROR">'."invalidLogDir : " . $dir .'</span>';
        } else if (!is_writable($dir)) {
            echo '<br/><span class="messageERROR">'. "lockedLogDir" . $dir .'</span>';
        } else {
            writeFile($msg, $file);
        }
    }
}


/**
 * 
 * ===========================================================================
 */
function writeFile($msg,$file)
{
    if (function_exists('error_log')) {
        return error_log($msg, 3, $file);
    } else {
        $handle=fopen($file, "a");
        if (! $handle) { return false;
        }
        if (! fwrite($handle, $msg)) { return false;
        }
        if (! fclose($handle)) {  return false;
        }
        return true;
    }
}

/**
 * ===========================================================================
 * Log tracing for debug to keep in the code
 * Will be used for debugQuery mode of for performance tracing
 * so can be considered as Trace log, but will generate a Debug message in log
 * Will be activated, depending on location, with :
 * $debugTrace=true
 * $debugQuery=true
 * or directly calling traceExecutionTime() function
 *
 * @param  $message message
 *          to store on log
 * @return void
 */
function debugTraceLog($message)
{
    logTracing($message, 3);
}

/**
 * ===========================================================================
 * Log tracing for general trace
 *
 * @param  $message message
 *          to store on log
 * @return void
 */
function traceLog($message)
{
    $debugTraceUpdates = Parameter::getGlobalParameter('debugTrace');
    if (isset($debugTraceUpdates) and $debugTraceUpdates == true) {
        logTracing($message, 2);
    }
}

/**
 * ===========================================================================
 * Log tracing for error
 *
 * @param  $message message
 *          to store on log
 * @return void
 */
function errorLog($message)
{
    logTracing($message, 1);
}

