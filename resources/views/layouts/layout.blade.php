<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/icono_gym.png" rel="icon">
    <script src="https://kit.fontawesome.com/4dd134ba8a.js" crossorigin="anonymous"></script>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    
    @vite(['resources/js/app.js'])
</head>

<body>
    <!-- Header Start -->
    @include('layouts/navbar')
    <!-- Header End -->


    @yield('content')
    

    <!-- Footer Start -->
    @include('layouts/footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-dark py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- Botón flotante del chatbot -->
    <div id="chatbot-icon">
        <i class="fa-solid fa-robot" style="color: #ffffff;"></i>
    </div>

    <!-- Ventana del chatbot -->
    <div id="chatbot-container">
        <div id="chatbot-header">
            <span>Chatbot Gym</span>
            <i class="fa-solid fa-rectangle-xmark fa-xl"></i>
        </div>
        <div id="chatbot-messages"></div>
        <input type="text" id="chatbot-input" placeholder="Escribe un mensaje..." onkeypress="sendMessage(event)">
    </div>



    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>

</html>
