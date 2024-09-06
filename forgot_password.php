<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #012239, #5d2020);
        }
        .forgot-password-container {
            background: #fff;
            padding: 50px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .forgot-password-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }
        .forgot-password-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .forgot-password-container input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        .forgot-password-container input:focus {
            border-color: #9b59b6;
            outline: none;
        }
        .forgot-password-container button {
            padding: 10px;
            border: none;
            border-radius: 30px;
            background-color:  #5d2020;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 150px;
        }
        .forgot-password-container button:hover {
            background-color: #9178ac;
        }
        .back-to-login {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        .back-to-login a {
            color: #9b59b6;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
        .code-container,
        .new-password-container {
            display: none;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <div class="email-container">
            <form id="emailForm" action="send_code.php" method="post">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="code-container">
            <form id="codeForm" action="verify_code.php" method="post">
                <input type="text" name="code" placeholder="Enter verification code" required>
                <button type="submit">Verify Code</button>
            </form>
        </div>
        <div class="new-password-container">
            <form id="passwordForm" action="reset_password.php" method="post">
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Reset Password</button>
                <p id="password-error" style="color: red; display: none;">Passwords do not match</p>
            </form>
        </div>
        <div class="back-to-login">
            Remember your password? <a href="login.html">Back to Login</a>
        </div>
    </div>

    <script>
        document.getElementById('emailForm').addEventListener('submit', function(event) {
            event.preventDefault();
            setTimeout(function() {
                document.querySelector('.email-container').style.display = 'none';
                document.querySelector('.code-container').style.display = 'block';
            }, 1000);
        });

        document.getElementById('codeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            setTimeout(function() {
                document.querySelector('.code-container').style.display = 'none';
                document.querySelector('.new-password-container').style.display = 'block';
            }, 1000);
        });

        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var error = document.getElementById('password-error');

            if (newPassword !== confirmPassword) {
                event.preventDefault();
                error.style.display = 'block';
            } else {
                error.style.display = 'none';
            }
        });
    </script>
</body>
</html>
