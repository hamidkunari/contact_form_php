<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $lastname  = $phone = $email= $message ="";
$name_err = $lastname_err  = $phone_err = $email_err = $message_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter an Lastname.";     
    } else{
        $lastname = $input_lastname;
    }
    
   

     $input_phone = trim($_POST["phone"]);
    if(empty($input_phone) && $input_phone < 8){
        $phone_err = "Please enter the phone.";     
    } elseif(!ctype_digit($input_phone)){
        $phone_err = "Please enter a phone.";
    } else{
        $phone = $input_phone;
    }
        
    
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an Email.";     
    } else{
        $email = $input_email;
    }

    $input_message = trim($_POST["message"]);
    if(empty($input_message)){
        $message_err = "Please enter an message.";     
    } else{
        $message = $input_message;
    }





    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, lastname,phone,email,message) VALUES (?, ?, ?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_lastname, $param_phone, $param_email , $param_message);
            
            // Set parameters
            $param_name = $name;
            $param_lastname = $lastname;
           
            $param_phone = $phone;
            $param_email = $email;
            $param_message = $message;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdgrcAZAAAAAPBFs339Oy-EqqBRBqYPkcJ-KRwi"></script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
                            <span class="help-block"><?php echo $lastname_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                            <label>Phone</label>
                            <input name="phone" class="form-control"><?php echo $phone; ?>
                            <span class="help-block"><?php echo $phone_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email Address</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>


                        <div class="form-group <?php echo (!empty($message_err)) ? 'has-error' : ''; ?>">
                            <label>Message</label>
                            <textarea  name="message" class="form-control"><?php echo $message; ?> </textarea>
                            <span class="help-block"><?php echo $message_err;?></span>
                        </div>




                        <input type="submit" value="Submit" class="btn btn-primary" >
                        
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<script>
      function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('reCAPTCHA_site_key', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
          });
        });
      }
  </script>