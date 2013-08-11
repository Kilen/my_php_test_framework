<?php

define("LF", "\n");
define("TEST_METHOD_PREFIX", "test_");
define("TEST_CLASS_PREFIX", "Test");
class PhpTezt
{
    public $is_successful;
    public $error_message;
    public function initialize()
    {
        $this -> is_successful = false;
        $this -> error_message = "";
    }
    
    public function should_equal($subject1, $subject2)
    {
        $this -> is_successful = true;
        if($subject1 != $subject2)
        {
            $this -> is_successful = false;
            $this -> error_message = $this -> generate_error_message("equlity test failed: param1($subject1) != param2($subject2)");
        }
    }

    public function generate_error_message($msg)
    {
        $caller_info = debug_backtrace()[1];
        $caller_line = $caller_info["line"];
        $caller_file = $caller_info["file"];
        $error_message = "(XXXXXX)" . $msg . LF
            . "In file ($caller_file) line ($caller_line)";
        return $error_message;
    }
}

?>

