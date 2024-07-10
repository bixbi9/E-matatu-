<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Document</title>
		<link href="{{ asset('css/login.css') }}" rel="stylesheet">

	</head>
	<body>
		<div id='login-form' class='login-page'>
			<div class="form-box">
				<div class='button-box'>
				 <div id='btn'></div>
				 <!-- <button
				 type='button'onclick='login()'class='togglt-btn'>Log in</button>
				 <button
				 type='button'onclick='register()'class='toggle-btn'>Register</button>
				</div>  -->
				<form id='login' class='input-group-login' action="home">
				 <input type='text'class='input-field'placeholder='User Id'required>
				 <input type='password'class='input-field'placeholder='Enter Password'required>
				 <input type='checkbox'class='check-box'><span>Remember Password</span>
				 <button type='submit'class='submit-btn'>Log in</button>
				</form>
				<form id='register'class='input-group-register'>
				 <input type='text'class='input-field'placeholder='First Name'required>
				 <input type='text'class='input-field'placeholder='Last Name'required>
				 <input type='text'class='input-field'placeholder='User Id'required>
				 <input type='password'class='input-field'placeholder='Enter Password'required>
				 <input type='password'class='input-field'placeholder='Confirm Password'required>
				 <input type='checkbox'class='check-box'><span>I agree to the terms and conditions</span>
				 <button type='submit'class='submit-btn'>Register</button>
			</form>
		</div>
	</body>
</html>
