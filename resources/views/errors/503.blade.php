<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- 404 with Ghost -->
        <div class="relative flex items-center justify-center text-[#e60000] font-extrabold text-[150px] leading-none tracking-tighter mb-4">
            <span>4</span>
            <div class="relative mx-3 w-[110px] h-[110px] rounded-full border-[22px] border-[#e60000] flex items-center justify-center bg-transparent z-10">
                <!-- Ghost SVG from Bootstrap Icons (or similar cute ghost) -->
                <svg class="absolute -top-[50px] -right-[40px] w-28 h-28 text-white drop-shadow-xl z-20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9v11.14c0 .85 1.05 1.25 1.62.62L8 19.38l1.38 1.38c.34.34.9.34 1.24 0L12 19.38l1.38 1.38c.34.34.9.34 1.24 0l1.38-1.38 1.38 1.38c.57.63 1.62.23 1.62-.62V9c0-3.87-3.13-7-7-7zm-1.5 7c-.83 0-1.5-.67-1.5-1.5S9.67 6 10.5 6 12 6.67 12 7.5 11.33 9 10.5 9zm3 0c-.83 0-1.5-.67-1.5-1.5S12.67 6 13.5 6 15 6.67 15 7.5 14.33 9 13.5 9zm-1.5 3.5c-1.38 0-2.5-.9-2.5-2h5c0 1.1-1.12 2-2.5 2z"/>
                    <!-- Ghost eyes (black) -->
                    <circle cx="10.5" cy="7.5" r="1.5" fill="#1f1f1f"/>
                    <circle cx="13.5" cy="7.5" r="1.5" fill="#1f1f1f"/>
                    <!-- Ghost mouth -->
                    <path d="M12 10.5c-1.38 0-2.5-.9-2.5-2h5c0 1.1-1.12 2-2.5 2z" fill="#1f1f1f"/>
                </svg>
            </div>
            <span>4</span>
        </div>
        
        <h1 class="text-[32px] font-bold text-gray-900 mb-3 tracking-wide">Page not found</h1>
        <p class="text-[13px] font-medium text-black max-w-sm mx-auto leading-relaxed">
            The Page you are looking for doesn't exist or site is under maintenance. Go back, or head over to <a href="/" class="text-[#e60000] hover:underline">Home</a>
        </p>
    </div>
</body>
</html>
