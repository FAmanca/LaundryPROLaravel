<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#93C5FD',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-bg {
            background-image: linear-gradient(135deg, rgba(59, 130, 246, 0.8) 0%, rgba(147, 197, 253, 0.9) 100%);
        }

        .search-box {
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>

<body>
    @yield('content')
    <script>
        feather.replace();

        // Simple animation for the search box
        document.addEventListener('DOMContentLoaded', function() {
            const searchBox = document.querySelector('.search-box');
            searchBox.style.transform = 'translateY(20px)';
            searchBox.style.opacity = '0';

            setTimeout(() => {
                searchBox.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                searchBox.style.transform = 'translateY(0)';
                searchBox.style.opacity = '1';
            }, 300);
        });
    </script>
</body>

</html>
