<?php
require_once './components/header.php';

require_once './db/db.connection.php';
require_once './helpers/validation.php';
require_once './helpers/functions.php';
require_once './controllers/user.controller.php';

$userTypes = User::showUserTypes($con);
$op_userTypes = $userTypes[1];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = Validation::filterData ($_POST['name']);
    $email = Validation::filterData($_POST['email']);
    $phone = Validation::filterData($_POST['phone']);
    $gender = Validation::filterData($_POST['gender']);
    $password = Validation::filterData($_POST['password']);
    $userType= Validation::filterData($_POST['usertype']);

    Validation::required_input('name',$name);
    Validation::validate_string('name',$name); 

    Validation::required_input('email',$email);
    Validation::checkEmail($email);

    Validation::required_input('phone',$phone);
    Validation::checkPhone($phone);

    Validation::required_input('gender', $gender);

    Validation::required_input('password', $password);
    Validation::minLength('password',$password,6);
    if($_POST['password'] != $_POST['confirmPassword']){
        Validation::$errors['password'] = 'Your password doesnot match confirm password';
    };

    Validation::required_input('user type', $userType);
    $userType = Validation::checkNumber('user type', $userType);

    $password = md5($password);
    if(count(Validation::$errors)== 0)
        User::register($con, $name, $email, $phone, $password, $gender, $userType);
}
?>
<div class="row row col-sm-12 col-lg-6 mx-auto mt-5">
    <div class="form-holder  ">
        <div class="form-content">
            <div class="form-items">
                <h3 class="mb-5" >Register</h3>
                <form class="requires-validation mb-5" action=<?php echo $_SERVER['PHP_SELF'] ?> method='post' enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" placeholder="Full Name" >
                        <?php
                            Helpers::echoError('name');
                        ?>
                    </div>

                    <div class="col-md-12">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" placeholder="E-mail Address" >
                        <?php
                            Helpers::echoError('email');
                        ?>
                    </div>

                    
                    <div class="col-md-12 d-flex flex-column my-3">
                        <label>gender</label>          
                        <select require name='gender' class="form-select mt-2" id="exampleInputPassword1" name="usertype">
                            <option disabled value="">--choose gender</option>
                            <option value="male">male</option>
                            <option value="female">female</option>
                        </select>
                        <?php
                            Helpers::echoError('gender');
                        ?>
                    </div>

                    <div class="col-md-12">
                        <label>Phone</label>
                        <input class="form-control" type="text" name="phone" placeholder="Your Phone number" >  
                        <?php
                            Helpers::echoError('phone');
                        ?>
                    </div>

                    <div class="col-md-12">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" >
                    </div>

                    <div class="col-md-12">
                        <label>Confirm Password</label>
                        <input class="form-control" type="password" name="confirmPassword" placeholder="Password" >
                    </div>

                    <div class="col-md-12 d-flex flex-column my-3">   
                        <label>User Type</label>       
                        <select  class="form-select mt-2" id="exampleInputPassword1" name="usertype">
                            <option disabled value="">--choose user type</option>
                            <?php
                                while ($row = mysqli_fetch_assoc($op_userTypes)) {
                            ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['userType']; ?></option>
                            <?php 
                                } 
                            ?>
                        </select>
                        <?php
                            Helpers::echoError('userType');
                        ?>
                    </div>
                    

                    <div class="form-button mt-3 d-flex">
                        <button id="submit" type="submit" class="button1 mx-auto my-3 btn btn-primary">Register</button>
                    </div>
                </form>
                <?php
                    require_once 'components/mssg.php';
                ?>
            </div>
        </div>
    </div>
</div>