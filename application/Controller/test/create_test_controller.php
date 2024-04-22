<?php
session_start();
include '../../Model/test/create_test_model.php';
include '../../Controller/main/header_controller.php';
include '../../Controller/main/footer_controller.php';
class CreateTestController{
    public function handleCreateTest(){


        $PostAction = $_POST['action'];
        if($PostAction == "create_test"){
            var_dump($_POST);
            $test_name = $_POST['test_name'];
            $test_time = $_POST['test_time'];
            $questions = $_POST['questions'];
            $response = CreateTest($test_name,$test_time,$questions );
            echo json_encode(['response' => $response]);
            exit;
        }


        $headerController = new HeaderController();
        $header = $headerController->showHeader();
        $footerController = new FooterController();
        $footer = $footerController->showFooter();
        $this->showView();



    }
    private function showView($errorMessage = '') {
        include '../../View/test/create_test_view.php';
    }

    public function CreateTest($test_name,$test_time,$questions){
        $createTestModel = new CreateTestModel();
        $responce = $createTestModel->CreateTest($test_name,$test_time,$questions);
        if($responce){return "success";}else{return "error";}
    }

}
var_dump($_POST);
$controller = new CreateTestController();
$controller->handleCreateTest();
?>



