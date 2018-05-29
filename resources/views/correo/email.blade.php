<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
	<br>
	<h1>Enviar Email</h1>
	<form action="send" method="post">
		 {{csrf_field()}}
		to: <input type="text" name="to">
		message: <textarea name="message" cols="30" rows="10"></textarea>
		<input type="submit" value="Send">

	</form>
</body>
</html>