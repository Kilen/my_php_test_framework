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
            $this -> handle_error("equlity test failed: param1($subject1) != param2($subject2)");
        }
    }

    public function should_not_equal($subject1, $subject2)
    {
        $this -> is_successful = true;
        if($subject1 == $subject2)
        {
            $this -> handle_error("not equlity test failed: param1($subject1) == param2($subject2)");
        }
    }

    // $subject1 > $subject2
    public function first_should_be_larger_than_second($subject1, $subject2)
    {
        $this -> is_successful = true;
        if($subject1 <= $subject2)
        {
            $this -> handle_error("compare test failed: param1($subject1) <= param2($subject2)");
        }
    }

    // $subject1 < $subject2
    public function first_should_be_smaller_than_second($subject1, $subject2)
    {
        $this -> is_successful = true;
        if($subject1 >= $subject2)
        {
            $this -> handle_error("compare test failed: param1($subject1) >= param2($subject2)");
        }
    }

    // when $subject = "", [], NULL
    public function should_be_empty($subject)
    {
        if($subject === "" || $subject === [] || $subject === NULL)
        {
            $this -> is_successful = true;
        }
        else
        {
            $this -> handle_error("empty test failed: param($subject1) is not empty");
        }
    }

    public function should_be_true($subject)
    {
        if($subject === true)
        {
            $this -> is_successful = true;
        }
        else
        {
            $this -> handle_error("true test failed: param($subject) is not true");
        }
    }

    public function should_be_false($subject)
    {
        if($subject === false)
        {
            $this -> is_successful = true;
        }
        else
        {
            $this -> handle_error("false test failed: param($subject) is not false");
        }
    }

    public function array_should_have_key($array, $key)
    {
        $this -> is_successful = false;
        foreach ($array as $k => $v) {
            if($key === $k)
            {
                $this -> is_successful = true;
                break;
            }
        }
        if($this -> is_successful === false)
        {
            $this -> handle_error("key existed test failed: array do not have key($key)");
        }
    }

    public function array_should_have_value($array, $value)
    {
        $this -> is_successful = false;
        foreach ($array as $v) {
            if($value === $v)
            {
                $this -> is_successful = true;
                break;
            }
        }
        if($this -> is_successful === false)
        {
            $this -> handle_error("value existed test failed: array do not have value($value)");
        }
    }

    private function handle_error($msg)
    {
        $this -> is_successful = false;
        $this -> error_message = $this -> generate_error_message($msg);
    }

    private function generate_error_message($msg)
    {
        $caller_info = debug_backtrace()[2];
        $caller_line = $caller_info["line"];
        $caller_file = $caller_info["file"];
        $error_message = "(XXXXXX)" . $msg . LF
            . "In file ($caller_file) line ($caller_line)";
        return $error_message;
    }
}

?>

