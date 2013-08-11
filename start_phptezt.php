<?php
function test_object($tester)
{
    $all_test_examples = collect_strings_with_specific_prefix(get_class_methods($tester), "test_");
    $number = count($all_test_examples);
    $class_name = get_class($tester);
    echo LF . LF . "In $class_name,You have $number test example(s):" . LF;

    $result = [
        "success_number" => 0,
        "failure_number" => 0
    ];
    $tester -> initialize();
    for ($i = 0; $i < $number; $i++) {
        echo "*********************" . LF;
        echo "Testing example $i, $all_test_examples[$i]():" . LF;
        eval("\$tester -> $all_test_examples[$i]();");
        if($tester -> is_successful)
        {
            $result["success_number"] += 1;
            echo "> Test example $all_test_examples[$i]() succeed!" . LF;
        }
        else
        {
            $result["failure_number"] += 1;
            echo "> " . $tester -> error_message . LF;
        }
    }
    return $result;
}

function collect_strings_with_specific_prefix($all_strings, $prefix)
{
    $expected_strings = [];
    foreach ($all_strings as $string) {
        if(is_begin_with($prefix, $string))
        {
            $expected_strings[] = $string;
        }
    }
    return $expected_strings;
}

function is_begin_with($prefix, $string)
{
    $string_prefix = substr($string, 0, strlen($prefix));
    return $string_prefix === $prefix;
}

function calculate_test_example_number($all_classes)
{
    $counter = 0;
    foreach ($all_classes as $class) {
        eval("\$tester = new $class;");
        $counter += count(collect_strings_with_specific_prefix(get_class_methods($tester), TEST_METHOD_PREFIX));
    }
    return $counter;
}

require_once("phptezt.php");
//require all the php files in phptezt/
foreach (glob("phptezt/*.php") as $filename)
{
    require_once $filename;
}

$all_test_classes = collect_strings_with_specific_prefix(get_declared_classes(), TEST_CLASS_PREFIX);
$test_example_number = calculate_test_example_number($all_test_classes);
$success_counter = 0;
$failure_counter = 0;
echo "You have $test_example_number test example(s) in total:" . LF;
foreach ($all_test_classes as $class) {
    eval("\$tester = new $class;");
    $result = test_object($tester);
    $success_counter += $result["success_number"];
    $failure_counter += $result["failure_number"];
}
echo LF . LF . "RESULT: $test_example_number example(s): succeed($success_counter), fail($failure_counter)" . LF; 
?>

