<?php
    ob_start();
    require_once 'components/header.php';
    require_once 'components/nav.php';
    require_once './helpers/validation.php';
    require_once './helpers/functions.php';
    require_once './db/db.connection.php';
    require_once './controllers/user.controller.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = Validation::filterData($_POST['email']);
        $password = Validation::filterData($_POST['password']);

        Validation::required_input('email', $email);
        Validation::checkEmail($email);

        Validation::required_input('password', $password);
        Validation::minLength('password', $password, 6);

        User::login($con, $email, $password);
    };
?>
<div class="row col-sm-12 col-lg-6 mx-auto mt-5">
    <div class="form-holder">
        <div class="form-content">
            <div class="form-items text-center">
                <h3 class="mb-5">Login</h3>
                <form method="post" action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>>
                    <div class="col-md-12">
                        <label>Email</label>
                        <input  type="email" name="email" placeholder="E-mail Address" required>
                        <?php
                            Helpers::echoError('email');
                        ?>
                    </div>

                    <div class="col-md-12">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="password" required>
                        <?php
                            Helpers::echoError('password');
                        ?>
                    </div>

                    <div class="form-button mt-3 d-flex">
                        <button id="submit" type="submit" class="button1 mx-auto my-3 btn btn-primary">Login</button>
                    </div>
                </form>
                <?php
                    require_once 'components/mssg.php';
                ?>
            </div>
        </div>
    </div>
</div>
