<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(dirname(__FILE__) . '/file_oper.php');

define("LF", "<br />");
class Test
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
            $bt = debug_backtrace();
            $caller_line = array_shift($bt)["line"];
            $this -> is_successful = false;
            $this -> error_message = "In line $caller_line Equality test failed: $subject1 != $subject2";
        }
    }
}

class Foo 
{
    public function A()
    {
        return "A";
    }
    public function B()
    {
        return "B";
    }
    public function C()
    {
        return "C";
    }
    private function D()
    {
        echo "Private function D is called";
    }
}

class TestFoo extends Test
{
    function test_file_oper_get_real_size()
    {
        $file_oper = new FILE_OPER;
        $this -> should_equal($file_oper -> get_real_size(1024), "1KB");
    }
}

function test_all_test_example($tester)
{
    $all_test_examples = get_test_examples($tester);
    $number = count($all_test_examples);
    echo "You have $number test example:" . LF;

    $success_counter = 0;
    $failure_counter = 0;
    $tester -> initialize();
    for ($i = 0; $i < $number; $i++) {
        echo "*********************" . LF;
        echo "Testing example $i, $all_test_examples[$i]():" . LF;
        eval("\$tester -> $all_test_examples[$i]();");
        if($tester -> is_successful)
        {
            $success_counter += 1;
            echo "Test example $all_test_examples[$i]() succeed!" . LF;
        }
        else
        {
            $failure_counter += 1;
            echo $tester -> error_message . LF;
        }
    }
    echo LF . LF . "RESULT: $number example(s): succeed($success_counter), fail($failure_counter)" . LF; 
}

function get_test_examples($tester)
{
    $all_public_methods = get_class_methods($tester);
    $test_examples = [];
    foreach ($all_public_methods as $method) {
        if(is_begin_with("test_", $method))
        {
            $test_examples[] = $method;
        }
    }
    return $test_examples;
}

function is_begin_with($prefix, $method_name)
{
    $method_name_prefix = substr($method_name, 0, strlen($prefix));
    return $method_name_prefix === $prefix;
}

$tester = new TestFoo;
test_all_test_example($tester);
?>

