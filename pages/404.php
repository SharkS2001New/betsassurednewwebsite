<?php
// Set the 404 status code header
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>404 Page Not Found | BetAssured</title>
<meta name="robots" content="noindex, follow">

<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

body{
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#f4f6f8;
    color:#333;
    display:flex;
    align-items:center;
    justify-content:center;
    height:100vh;
    text-align:center;
}

.container{
    background:white;
    padding:3rem;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    max-width:520px;
    width:90%;
}

h1{
    font-size:6rem;
    font-weight:800;
    color:#e74c3c;
    margin-bottom:0.5rem;
}

h2{
    font-size:1.6rem;
    margin-bottom:1rem;
    color:#2c3e50;
}

p{
    color:#666;
    margin-bottom:2rem;
    line-height:1.6;
}

.btn{
    display:inline-block;
    background:#10396e;
    color:white;
    padding:12px 32px;
    text-decoration:none;
    border-radius:6px;
    font-weight:600;
    transition:all .25s ease;
}

.btn:hover{
    background:#0c2c55;
    transform:translateY(-2px);
}

.footer-text{
    margin-top:2rem;
    font-size:0.8rem;
    color:#999;
}
</style>

</head>

<body>

<div class="container">

<h1>404</h1>

<h2>Page Not Found</h2>

<p>
The page you are looking for may have been moved, deleted, or never existed.  
Return to the homepage to continue exploring the latest football predictions and betting tips on BetAssured.
</p>

<a href="/" class="btn">Go to Homepage</a>

<div class="footer-text">
© <?php echo date("Y"); ?> BetAssured
</div>

</div>

</body>
</html>