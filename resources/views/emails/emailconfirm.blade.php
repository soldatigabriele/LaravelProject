<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h3>Account Verification</h3>
Confirm your email address<br>
<br>
Confirmation link: {{env('APP_URL')}}/registerotheremail?code={{ $user['confirmation_code'] }}
<br>
</body>
</html>
