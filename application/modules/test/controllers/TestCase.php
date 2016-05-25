<?php

/**
 * TestCase base class to all the test classes.
 */

abstract class TestCase extends MX_Controller{

    const REPORT_PATH = "test/test_report";
  
    private $child;

    public function __construct($child){
        
        parent::__construct();

        $this->child = $child;
        
        $this->load->library('unit_test');

        $tableTemplate = "
          <h2><i class='fa fa-pencil-square-o'></i></h2>
          <div class=\"box-body table-responsive no-padding\">
          <table class=\"table table-bordered table-hover\" cellpadding=\"4\" cellspacing=\"1\">
          <tbody>

              {rows}
              <tr>
              <td class=\"text-center\"><b>{item}</b></td>
              <td class=\"text-center\">{result}</td>
              </tr>
              {/rows}

          </tbody>
          </table>
          </div>
          ";

        $this->unit->set_template($tableTemplate); 

        // Set this to TRUE to check the type of the variable on tests
        $this->unit->use_strict(TRUE);

        // Set this to TRUE to run the tests
        $this->unit->active(TRUE);
    }

    private function run(){

        $child = $this->child;

        $testClass = new ReflectionClass(get_class($child));

        $tests = $testClass->getMethods();

        foreach($tests as $test){
            
            // Get the methods only for the child (the class that contains the tests)
            if($test->class === get_class($child)){

                $testName = $test->name;

                // Check if the method(test) exists
                if(method_exists($child, $testName)){

                    // No need to run the construct and the index
                    if($testName !== "__construct" && $testName !== "index"){
                        
                        // Run the tests
                        $child->{$testName}();
                    }
                }else{

                    // Using AdminLTE callout and callout() method defined in tables_helper
                    callout("danger", "O teste '".$testName."'() não existe.");
                }
            }
        }
    }

    public function index(){

        $this->run();

        $test_report = array(
            'unit_report' => $this->unit->report(),
            'passedTests' => $this->unit->passed_tests(),
            'failedTests' => $this->unit->failed_tests(),
            'classUnderTest' => get_class($this->child)
        );

        $this->load->test_template(self::REPORT_PATH, $test_report);
    }
}
