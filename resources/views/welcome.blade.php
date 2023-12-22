<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-primary-dark-gray scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Venture - Revolutionizing Homeschooling with OpenAI">
    <meta name="description" content="Discover Venture - Revolutionizing Homeschooling with OpenAI. Our platform offers personalized, AI-enhanced learning experiences tailored for your child's unique educational journey. Embrace the future of education with cutting-edge technology and expert support, ensuring a comprehensive, engaging, and effective homeschooling environment. Join Venture and unlock your child's full potential.">
    <meta name="image" content="{{ asset('assets/images/preview-image.png') }}">
    <meta name="type" content="website">
    <meta name="url" content="https://learnwithventure.com">


    <meta property="og:title" content="Venture - Revolutionizing Homeschooling with OpenAI">
    <meta property="og:description" content="Discover Venture - Revolutionizing Homeschooling with OpenAI. Our platform offers personalized, AI-enhanced learning experiences tailored for your child's unique educational journey. Embrace the future of education with cutting-edge technology and expert support, ensuring a comprehensive, engaging, and effective homeschooling environment. Join Venture and unlock your child's full potential.">
    <meta property="og:image" content="{{ asset('assets/images/preview-image.png') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://learnwithventure.com">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon"/>

    @vite(['resources/css/app.css'])
    <script>
        document.documentElement.classList.add('js')
    </script>

    <!-- Fathom - beautiful, simple website analytics -->
    <script src="https://cdn.usefathom.com/script.js" data-site="KVCJTXET" defer></script>
    <!-- / Fathom -->
</head>

<body class="antialiased relative">
    <header class="sticky top-0 bg-primary-dark-gray z-20">
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="#" class="flex items-center -m-1.5 p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block h-12">
                        <path
                            d="M 239 51.091 C 217.746 55.212, 195.337 80.006, 180.940 115.334 C 178.575 121.137, 178.544 121.165, 175.031 120.588 C 173.089 120.269, 162.725 119.727, 152 119.385 C 135.936 118.871, 130.914 119.060, 123.500 120.458 C 93.398 126.132, 76.179 143.807, 75.662 169.561 C 75.500 177.607, 75.493 177.629, 71.592 181.054 C 62.983 188.612, 60.379 202.348, 65.578 212.780 C 70.298 222.252, 78.230 226.943, 89.588 226.978 L 96.676 227 102.705 234.778 L 108.734 242.556 105.788 246.028 C 100.246 252.559, 88.083 271.540, 84.579 279.123 C 72.778 304.664, 72.705 326.870, 84.370 342.716 C 98.944 362.513, 129.789 370.091, 173.020 364.493 L 178.540 363.779 180.929 369.639 C 195.142 404.512, 215.803 427.807, 237.542 433.469 C 251.856 437.197, 266.496 433.580, 279.869 423.010 C 285.094 418.879, 285.142 418.865, 290.093 419.927 C 299.030 421.843, 309.076 418.234, 315.415 410.828 C 319.031 406.604, 322 399.016, 322 394 C 322 389.592, 319.097 381.269, 316.471 378.148 L 313.991 375.201 316.176 369.493 L 318.360 363.785 322.430 364.374 C 362.093 370.113, 391.441 364.512, 408.080 348.028 C 429.922 326.389, 425.055 290.599, 394.857 250.777 C 391.636 246.529, 389 242.807, 389 242.505 C 389 242.204, 391.192 239.154, 393.872 235.729 C 396.552 232.303, 400.350 226.976, 402.312 223.892 L 405.880 218.283 412.219 217.775 C 419.672 217.176, 425.084 214.636, 430.201 209.335 C 435.227 204.128, 437 199.405, 437 191.228 C 437 181.892, 433.741 175.349, 426.383 169.914 C 422.091 166.743, 421 165.367, 421 163.125 C 421 161.578, 420.087 157.655, 418.971 154.407 C 413.377 138.119, 400.611 127.599, 379.500 121.878 C 372.741 120.046, 368.630 119.694, 353 119.605 C 342.825 119.547, 330.831 119.880, 326.348 120.345 C 318.333 121.177, 318.183 121.151, 317.487 118.845 C 315.702 112.933, 309.296 99.575, 303.671 90.034 C 295.956 76.948, 280.856 61.117, 271.419 56.219 C 260.497 50.550, 250.196 48.921, 239 51.091 M 239 63.878 C 222.467 69.302, 206.528 87.894, 194.685 115.567 L 191.232 123.635 204.866 127.474 C 217.088 130.915, 235.493 137.267, 245 141.324 C 248.277 142.722, 248.882 142.661, 254.500 140.356 C 265.042 136.030, 280.139 130.949, 292.915 127.426 C 299.744 125.543, 305.466 123.867, 305.632 123.701 C 306.269 123.065, 296.748 103.130, 292.236 95.651 C 283.219 80.708, 274.476 71.824, 263 65.946 C 259.560 64.185, 256.498 63.573, 250 63.350 C 245.325 63.189, 240.375 63.427, 239 63.878 M 130.500 132.139 C 115.385 134.053, 103.666 139.241, 96.529 147.178 C 91.615 152.644, 89.182 158.702, 88.326 167.601 L 87.739 173.703 92.077 174.396 C 112.524 177.666, 122.575 199.862, 110.905 215.973 C 109.307 218.178, 108 220.246, 108 220.567 C 108 220.889, 109.996 223.746, 112.436 226.916 L 116.871 232.681 127.686 222.244 C 133.634 216.503, 142.721 208.363, 147.881 204.153 C 153.040 199.944, 158.324 195.600, 159.622 194.500 C 161.563 192.856, 162.245 190.721, 163.459 182.500 C 165.463 168.923, 168.488 154.376, 171.631 143.206 C 173.069 138.095, 174.078 133.749, 173.873 133.550 C 173.103 132.802, 151.376 131.005, 144.576 131.126 C 140.684 131.196, 134.350 131.652, 130.500 132.139 M 332.530 132.118 C 327.597 132.662, 323.413 133.254, 323.232 133.435 C 323.051 133.616, 324.017 137.754, 325.379 142.632 C 328.480 153.739, 332.435 172.810, 333.995 184.175 L 335.185 192.851 341.343 197.612 C 349.831 204.175, 360.846 213.848, 371.359 223.970 L 380.218 232.500 383.272 228.765 C 384.952 226.710, 388.225 222.235, 390.546 218.820 L 394.765 212.612 391.721 209.567 C 384.001 201.848, 382.213 188.892, 387.520 179.141 C 391.247 172.293, 396.034 168.348, 403.208 166.213 C 409.743 164.268, 409.832 163.782, 405.385 154.358 C 400.746 144.527, 389.098 136.664, 374.412 133.451 C 365.601 131.523, 344.134 130.840, 332.530 132.118 M 185.603 140.750 C 182.679 149.663, 175.540 182, 176.496 182 C 176.611 182, 183.039 178.042, 190.782 173.205 C 198.525 168.368, 210.959 161.281, 218.413 157.455 C 225.868 153.630, 231.975 150.229, 231.984 149.898 C 232.016 148.710, 191.686 136.017, 187.830 136.003 C 187.462 136.001, 186.460 138.137, 185.603 140.750 M 297.229 138.946 C 285.174 142.264, 265 149.174, 265 149.985 C 265 150.295, 269.387 152.719, 274.750 155.372 C 285.435 160.659, 305.171 172.065, 314.327 178.246 C 317.532 180.409, 320.332 182.002, 320.548 181.785 C 321.149 181.185, 315.364 153.562, 312.741 144.507 C 311.298 139.524, 309.875 136.439, 308.963 136.316 C 308.158 136.207, 302.878 137.391, 297.229 138.946 M 231 164.848 C 213.851 173.475, 199.715 181.757, 184.315 192.199 L 174.131 199.105 173.315 204.802 C 172.247 212.269, 172.260 272.815, 173.331 280.324 L 174.162 286.147 188.331 295.478 C 204.647 306.223, 225.260 317.947, 239.088 324.346 L 248.675 328.784 260.588 323.069 C 276.660 315.357, 293.710 305.568, 309.663 294.891 C 321.815 286.758, 322.970 285.719, 323.702 282.264 C 324.755 277.299, 324.735 209.876, 323.679 203.683 L 322.857 198.866 308.679 189.527 C 286.432 174.874, 253.126 156.955, 248.266 157.026 C 247.295 157.040, 239.525 160.560, 231 164.848 M 406.643 177.983 C 394.416 181.839, 393.663 198.867, 405.487 204.097 C 414.761 208.200, 424.993 201.592, 424.993 191.500 C 424.993 181.971, 415.700 175.126, 406.643 177.983 M 81.346 189.054 C 73.153 194.303, 73.224 207.169, 81.476 212.576 C 85.306 215.086, 91.186 215.480, 95.345 213.507 C 104.924 208.961, 105.725 194.462, 96.701 188.960 C 92.455 186.370, 85.469 186.413, 81.346 189.054 M 233.762 196.942 C 219.272 201.574, 206.851 214.319, 202.585 228.932 C 200.545 235.923, 200.551 249.110, 202.598 256.068 C 206.969 270.924, 220.100 284.056, 234.932 288.407 C 241.843 290.434, 255.098 290.457, 261.844 288.454 C 282.262 282.390, 296.249 263.622, 296.166 242.400 C 296.084 221.404, 282.665 203.043, 262.909 196.895 C 254.914 194.406, 241.627 194.428, 233.762 196.942 M 238.880 208.316 C 227.805 211.454, 217.604 221.676, 214.339 232.906 C 209.677 248.943, 218.366 267.489, 234 274.871 C 238.651 277.068, 240.889 277.469, 248.500 277.469 C 256.065 277.469, 258.377 277.059, 263.002 274.903 C 270.014 271.634, 277.080 264.574, 280.682 257.239 C 283.154 252.205, 283.495 250.395, 283.459 242.500 C 283.424 234.764, 283.029 232.697, 280.652 227.783 C 277.042 220.322, 270.297 213.483, 263.460 210.353 C 256.792 207.300, 245.706 206.381, 238.880 208.316 M 149.500 218.921 C 144 223.795, 136.223 231.120, 132.217 235.198 L 124.935 242.613 136.217 253.560 C 142.423 259.581, 150.200 266.816, 153.500 269.636 L 159.500 274.765 159.500 242.382 C 159.500 224.572, 159.500 210.013, 159.500 210.029 C 159.500 210.045, 155 214.047, 149.500 218.921 M 337.500 242.474 L 337.500 274.715 346 267.415 C 350.675 263.399, 358.452 256.128, 363.282 251.255 L 372.063 242.397 359.782 230.433 C 353.027 223.853, 345.250 216.616, 342.500 214.351 L 337.500 210.233 337.500 242.474 M 113.514 256.456 C 102.866 269.577, 93.086 288.301, 89.879 301.710 C 82.335 333.252, 100.416 351.824, 140.500 353.706 C 150.209 354.162, 172.586 352.737, 173.742 351.589 C 173.876 351.457, 172.211 344.294, 170.043 335.672 C 167.876 327.050, 165.115 313.738, 163.909 306.088 L 161.715 292.180 150.608 283.188 C 144.498 278.242, 134.392 269.295, 128.148 263.304 L 116.796 252.413 113.514 256.456 M 368.096 264.148 C 361.294 270.656, 351.128 279.630, 345.505 284.090 L 335.282 292.200 333.060 306.350 C 331.838 314.132, 329.073 327.436, 326.916 335.913 C 324.759 344.391, 323.107 351.440, 323.247 351.578 C 324.412 352.735, 346.744 354.164, 356.500 353.706 C 402.078 351.566, 419.181 327.524, 402.918 288.458 C 399.353 279.894, 392.212 267.833, 385.302 258.705 L 380.464 252.314 368.096 264.148 M 177.588 310.472 C 180.314 326.147, 186.390 349.003, 187.830 348.997 C 191.728 348.983, 232 336.291, 232 335.077 C 232 334.733, 227.613 332.281, 222.250 329.628 C 211.629 324.373, 191.846 312.946, 182.805 306.844 C 179.673 304.730, 176.925 303, 176.699 303 C 176.473 303, 176.873 306.362, 177.588 310.472 M 314.500 306.619 C 307.260 311.700, 290.315 321.705, 276.585 329.006 L 264.670 335.342 274.085 338.756 C 287.454 343.604, 309.035 349.369, 310.021 348.356 C 311.639 346.693, 321 308.553, 321 303.624 C 321 302.427, 319.466 303.134, 314.500 306.619 M 242.473 344.661 C 229.700 349.912, 212.195 355.590, 195.299 359.963 L 191.098 361.051 194.617 369.275 C 214.619 416.014, 243.139 433.476, 269.589 415.178 L 274.679 411.658 271.652 405.789 C 266.036 394.900, 268.224 382.547, 277.207 374.432 C 283.080 369.126, 287.283 367.624, 296.079 367.690 C 303.632 367.746, 303.661 367.736, 304.811 364.689 C 305.446 363.008, 305.861 361.539, 305.733 361.426 C 305.605 361.313, 301.088 360.025, 295.695 358.564 C 285.959 355.926, 263.104 348.180, 253.473 344.254 L 248.446 342.205 242.473 344.661 M 287.500 381.988 C 281.271 386.087, 279.231 394.586, 282.995 400.759 C 286.099 405.851, 290.601 408.189, 296.346 407.693 C 309.381 406.569, 313.789 388.720, 302.701 381.960 C 298.474 379.382, 291.440 379.395, 287.500 381.988"
                            stroke="none" fill="#fccc07" fill-rule="evenodd" />
                    </svg>
                    <span class="uppercase text-white text-2xl tracking-widest">Venture</span>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button onclick="toggleMobileMenu()" type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-primary-yellow">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="#benefits" class="text-sm font-semibold leading-6 text-white">BENEFITS</a>
                <a href="#subjects" class="text-sm font-semibold leading-6 text-white">SUBJECTS</a>
                <a href="#pricing" class="text-sm font-semibold leading-6 text-white">PRICING</a>
                <a href="#reviews" class="text-sm font-semibold leading-6 text-white">REVIEWS</a>
                <a href="#faq" class="text-sm font-semibold leading-6 text-white">FAQ</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <div class="flex flex-col text-right">
                    <a href="/auth-options" class="text-sm font-semibold leading-6 text-white">Log in <span
                            aria-hidden="true">&rarr;</span></a>
                    <a href="/register"
                        class="inline-flex items-center px-4 py-2 bg-primary-yellow border border-transparent rounded-md font-semibold text-xs text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-dark-gray focus:ring-offset-2 dark:focus:ring-offset-primary-dark-gray transition ease-in-out duration-150">SIGN
                        UP NOW</a>
                </div>
            </div>
        </nav>
        <!-- Mobile menu, show/hide based on menu open state. -->
        <div id="mobileMenu" class="hidden" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on slide-over state. -->
            <div class="fixed inset-0 z-10"></div>
            <div
                class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-primary-gray px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
                <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Venture</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block h-8">
                            <path
                                d="M 239 51.091 C 217.746 55.212, 195.337 80.006, 180.940 115.334 C 178.575 121.137, 178.544 121.165, 175.031 120.588 C 173.089 120.269, 162.725 119.727, 152 119.385 C 135.936 118.871, 130.914 119.060, 123.500 120.458 C 93.398 126.132, 76.179 143.807, 75.662 169.561 C 75.500 177.607, 75.493 177.629, 71.592 181.054 C 62.983 188.612, 60.379 202.348, 65.578 212.780 C 70.298 222.252, 78.230 226.943, 89.588 226.978 L 96.676 227 102.705 234.778 L 108.734 242.556 105.788 246.028 C 100.246 252.559, 88.083 271.540, 84.579 279.123 C 72.778 304.664, 72.705 326.870, 84.370 342.716 C 98.944 362.513, 129.789 370.091, 173.020 364.493 L 178.540 363.779 180.929 369.639 C 195.142 404.512, 215.803 427.807, 237.542 433.469 C 251.856 437.197, 266.496 433.580, 279.869 423.010 C 285.094 418.879, 285.142 418.865, 290.093 419.927 C 299.030 421.843, 309.076 418.234, 315.415 410.828 C 319.031 406.604, 322 399.016, 322 394 C 322 389.592, 319.097 381.269, 316.471 378.148 L 313.991 375.201 316.176 369.493 L 318.360 363.785 322.430 364.374 C 362.093 370.113, 391.441 364.512, 408.080 348.028 C 429.922 326.389, 425.055 290.599, 394.857 250.777 C 391.636 246.529, 389 242.807, 389 242.505 C 389 242.204, 391.192 239.154, 393.872 235.729 C 396.552 232.303, 400.350 226.976, 402.312 223.892 L 405.880 218.283 412.219 217.775 C 419.672 217.176, 425.084 214.636, 430.201 209.335 C 435.227 204.128, 437 199.405, 437 191.228 C 437 181.892, 433.741 175.349, 426.383 169.914 C 422.091 166.743, 421 165.367, 421 163.125 C 421 161.578, 420.087 157.655, 418.971 154.407 C 413.377 138.119, 400.611 127.599, 379.500 121.878 C 372.741 120.046, 368.630 119.694, 353 119.605 C 342.825 119.547, 330.831 119.880, 326.348 120.345 C 318.333 121.177, 318.183 121.151, 317.487 118.845 C 315.702 112.933, 309.296 99.575, 303.671 90.034 C 295.956 76.948, 280.856 61.117, 271.419 56.219 C 260.497 50.550, 250.196 48.921, 239 51.091 M 239 63.878 C 222.467 69.302, 206.528 87.894, 194.685 115.567 L 191.232 123.635 204.866 127.474 C 217.088 130.915, 235.493 137.267, 245 141.324 C 248.277 142.722, 248.882 142.661, 254.500 140.356 C 265.042 136.030, 280.139 130.949, 292.915 127.426 C 299.744 125.543, 305.466 123.867, 305.632 123.701 C 306.269 123.065, 296.748 103.130, 292.236 95.651 C 283.219 80.708, 274.476 71.824, 263 65.946 C 259.560 64.185, 256.498 63.573, 250 63.350 C 245.325 63.189, 240.375 63.427, 239 63.878 M 130.500 132.139 C 115.385 134.053, 103.666 139.241, 96.529 147.178 C 91.615 152.644, 89.182 158.702, 88.326 167.601 L 87.739 173.703 92.077 174.396 C 112.524 177.666, 122.575 199.862, 110.905 215.973 C 109.307 218.178, 108 220.246, 108 220.567 C 108 220.889, 109.996 223.746, 112.436 226.916 L 116.871 232.681 127.686 222.244 C 133.634 216.503, 142.721 208.363, 147.881 204.153 C 153.040 199.944, 158.324 195.600, 159.622 194.500 C 161.563 192.856, 162.245 190.721, 163.459 182.500 C 165.463 168.923, 168.488 154.376, 171.631 143.206 C 173.069 138.095, 174.078 133.749, 173.873 133.550 C 173.103 132.802, 151.376 131.005, 144.576 131.126 C 140.684 131.196, 134.350 131.652, 130.500 132.139 M 332.530 132.118 C 327.597 132.662, 323.413 133.254, 323.232 133.435 C 323.051 133.616, 324.017 137.754, 325.379 142.632 C 328.480 153.739, 332.435 172.810, 333.995 184.175 L 335.185 192.851 341.343 197.612 C 349.831 204.175, 360.846 213.848, 371.359 223.970 L 380.218 232.500 383.272 228.765 C 384.952 226.710, 388.225 222.235, 390.546 218.820 L 394.765 212.612 391.721 209.567 C 384.001 201.848, 382.213 188.892, 387.520 179.141 C 391.247 172.293, 396.034 168.348, 403.208 166.213 C 409.743 164.268, 409.832 163.782, 405.385 154.358 C 400.746 144.527, 389.098 136.664, 374.412 133.451 C 365.601 131.523, 344.134 130.840, 332.530 132.118 M 185.603 140.750 C 182.679 149.663, 175.540 182, 176.496 182 C 176.611 182, 183.039 178.042, 190.782 173.205 C 198.525 168.368, 210.959 161.281, 218.413 157.455 C 225.868 153.630, 231.975 150.229, 231.984 149.898 C 232.016 148.710, 191.686 136.017, 187.830 136.003 C 187.462 136.001, 186.460 138.137, 185.603 140.750 M 297.229 138.946 C 285.174 142.264, 265 149.174, 265 149.985 C 265 150.295, 269.387 152.719, 274.750 155.372 C 285.435 160.659, 305.171 172.065, 314.327 178.246 C 317.532 180.409, 320.332 182.002, 320.548 181.785 C 321.149 181.185, 315.364 153.562, 312.741 144.507 C 311.298 139.524, 309.875 136.439, 308.963 136.316 C 308.158 136.207, 302.878 137.391, 297.229 138.946 M 231 164.848 C 213.851 173.475, 199.715 181.757, 184.315 192.199 L 174.131 199.105 173.315 204.802 C 172.247 212.269, 172.260 272.815, 173.331 280.324 L 174.162 286.147 188.331 295.478 C 204.647 306.223, 225.260 317.947, 239.088 324.346 L 248.675 328.784 260.588 323.069 C 276.660 315.357, 293.710 305.568, 309.663 294.891 C 321.815 286.758, 322.970 285.719, 323.702 282.264 C 324.755 277.299, 324.735 209.876, 323.679 203.683 L 322.857 198.866 308.679 189.527 C 286.432 174.874, 253.126 156.955, 248.266 157.026 C 247.295 157.040, 239.525 160.560, 231 164.848 M 406.643 177.983 C 394.416 181.839, 393.663 198.867, 405.487 204.097 C 414.761 208.200, 424.993 201.592, 424.993 191.500 C 424.993 181.971, 415.700 175.126, 406.643 177.983 M 81.346 189.054 C 73.153 194.303, 73.224 207.169, 81.476 212.576 C 85.306 215.086, 91.186 215.480, 95.345 213.507 C 104.924 208.961, 105.725 194.462, 96.701 188.960 C 92.455 186.370, 85.469 186.413, 81.346 189.054 M 233.762 196.942 C 219.272 201.574, 206.851 214.319, 202.585 228.932 C 200.545 235.923, 200.551 249.110, 202.598 256.068 C 206.969 270.924, 220.100 284.056, 234.932 288.407 C 241.843 290.434, 255.098 290.457, 261.844 288.454 C 282.262 282.390, 296.249 263.622, 296.166 242.400 C 296.084 221.404, 282.665 203.043, 262.909 196.895 C 254.914 194.406, 241.627 194.428, 233.762 196.942 M 238.880 208.316 C 227.805 211.454, 217.604 221.676, 214.339 232.906 C 209.677 248.943, 218.366 267.489, 234 274.871 C 238.651 277.068, 240.889 277.469, 248.500 277.469 C 256.065 277.469, 258.377 277.059, 263.002 274.903 C 270.014 271.634, 277.080 264.574, 280.682 257.239 C 283.154 252.205, 283.495 250.395, 283.459 242.500 C 283.424 234.764, 283.029 232.697, 280.652 227.783 C 277.042 220.322, 270.297 213.483, 263.460 210.353 C 256.792 207.300, 245.706 206.381, 238.880 208.316 M 149.500 218.921 C 144 223.795, 136.223 231.120, 132.217 235.198 L 124.935 242.613 136.217 253.560 C 142.423 259.581, 150.200 266.816, 153.500 269.636 L 159.500 274.765 159.500 242.382 C 159.500 224.572, 159.500 210.013, 159.500 210.029 C 159.500 210.045, 155 214.047, 149.500 218.921 M 337.500 242.474 L 337.500 274.715 346 267.415 C 350.675 263.399, 358.452 256.128, 363.282 251.255 L 372.063 242.397 359.782 230.433 C 353.027 223.853, 345.250 216.616, 342.500 214.351 L 337.500 210.233 337.500 242.474 M 113.514 256.456 C 102.866 269.577, 93.086 288.301, 89.879 301.710 C 82.335 333.252, 100.416 351.824, 140.500 353.706 C 150.209 354.162, 172.586 352.737, 173.742 351.589 C 173.876 351.457, 172.211 344.294, 170.043 335.672 C 167.876 327.050, 165.115 313.738, 163.909 306.088 L 161.715 292.180 150.608 283.188 C 144.498 278.242, 134.392 269.295, 128.148 263.304 L 116.796 252.413 113.514 256.456 M 368.096 264.148 C 361.294 270.656, 351.128 279.630, 345.505 284.090 L 335.282 292.200 333.060 306.350 C 331.838 314.132, 329.073 327.436, 326.916 335.913 C 324.759 344.391, 323.107 351.440, 323.247 351.578 C 324.412 352.735, 346.744 354.164, 356.500 353.706 C 402.078 351.566, 419.181 327.524, 402.918 288.458 C 399.353 279.894, 392.212 267.833, 385.302 258.705 L 380.464 252.314 368.096 264.148 M 177.588 310.472 C 180.314 326.147, 186.390 349.003, 187.830 348.997 C 191.728 348.983, 232 336.291, 232 335.077 C 232 334.733, 227.613 332.281, 222.250 329.628 C 211.629 324.373, 191.846 312.946, 182.805 306.844 C 179.673 304.730, 176.925 303, 176.699 303 C 176.473 303, 176.873 306.362, 177.588 310.472 M 314.500 306.619 C 307.260 311.700, 290.315 321.705, 276.585 329.006 L 264.670 335.342 274.085 338.756 C 287.454 343.604, 309.035 349.369, 310.021 348.356 C 311.639 346.693, 321 308.553, 321 303.624 C 321 302.427, 319.466 303.134, 314.500 306.619 M 242.473 344.661 C 229.700 349.912, 212.195 355.590, 195.299 359.963 L 191.098 361.051 194.617 369.275 C 214.619 416.014, 243.139 433.476, 269.589 415.178 L 274.679 411.658 271.652 405.789 C 266.036 394.900, 268.224 382.547, 277.207 374.432 C 283.080 369.126, 287.283 367.624, 296.079 367.690 C 303.632 367.746, 303.661 367.736, 304.811 364.689 C 305.446 363.008, 305.861 361.539, 305.733 361.426 C 305.605 361.313, 301.088 360.025, 295.695 358.564 C 285.959 355.926, 263.104 348.180, 253.473 344.254 L 248.446 342.205 242.473 344.661 M 287.500 381.988 C 281.271 386.087, 279.231 394.586, 282.995 400.759 C 286.099 405.851, 290.601 408.189, 296.346 407.693 C 309.381 406.569, 313.789 388.720, 302.701 381.960 C 298.474 379.382, 291.440 379.395, 287.500 381.988"
                                stroke="none" fill="#fccc07" fill-rule="evenodd" />
                        </svg>
                    </a>
                    <button onclick="toggleMobileMenu()" type="button" class="-m-2.5 rounded-md p-2.5 text-primary-yellow">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/25">
                        <div class="space-y-2 py-6">
                            <a href="#benefits"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">BENEFITS</a>
                            <a href="#subjects"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">SUBJECTS</a>
                            <a href="#pricing"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">PRICING</a>
                            <a href="#reviews"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">REVIEWS</a>
                            <a href="#faq"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">FAQ</a>
                        </div>
                        <div class="py-6">
                            <a href="/auth-options" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">Log in</a>
                            <a href="/register" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-white hover:bg-primary-dark-gray">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div>
        <div class="max-w-7xl mx-auto">
            <section id="hero" class="mt-10 lg:mt-32">
                <div class="flex justify-center">
                    <div class="px-10 w-full space-y-8 lg:px-0 md:w-2/3 xl:w-8/12">
                        <h1 class="text-4xl text-white text-center font-thin lg:text-7xl">Curiosity-led learning for
                            creative minds,
                            encouraging exploration and discovery.</h1>
                        <div class="flex justify-center">
                            <a href="/register"
                                class="inline-flex items-center px-6 py-4 bg-primary-yellow border border-transparent rounded-md font-semibold text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-dark-gray focus:ring-offset-2 dark:focus:ring-offset-primary-dark-gray transition ease-in-out duration-150">LEARN
                                WITH VENTURE</a>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-10 md:grid-cols-5">
                    <div onclick="toggleResponse(this)" class="relative transition ease-in-out delay-150 duration-300 hover:scale-110">
                        <img src="{{ asset('assets/images/hero/1.png')}}" alt="spaceship launching into space" class="w-full h-full cursor-pointer" />
                        <div class="absolute hidden bg-primary-gray text-white p-4 w-96 h-full left-60 top-0 animate-fade-in-right">Hey there, space enthusiast! Space exploration is super exciting, but it's not without its challenges. One biggie is the crazy cost - shooting things into space is seriously pricey! Another hurdle is the extreme conditions out there, like intense radiation and bone-weakening microgravity. And let's not forget about the mind-boggling distance - even getting to our nearest planet neighbor, Mars, is a huge feat!</div>
                    </div>
                    <div onclick="toggleResponse(this)" class="relative transition ease-in-out delay-150 duration-300 hover:scale-110">
                        <img src="{{ asset('assets/images/hero/2.png')}}" alt="chameleon on a stick" class="w-full h-full cursor-pointer" />
                        <div class="absolute hidden bg-primary-gray text-white p-4 w-96 h-full left-60 top-0 animate-fade-in-right overflow-y-auto">
                            <p>Hey there! So, chameleons are like the rockstars of the animal kingdom when it comes to changing colors. They change color to communicate, regulate their temperature, and camouflage themselves.</p>
                            <br>
                            <p>Okay, get this: chameleons have special cells called "chromatophores" in their skin. These cells have different colors stored inside them, like a wardrobe full of colorful clothes. When chameleons want to change color, their brain sends signals to these cells, telling them which color to show. It's kind of like a remote-controlled LED light changing colors!</p>
                        </div>
                    </div>
                    <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                        <img src="{{ asset('assets/images/hero/3.png')}}" alt="sphynx with pyramid in the background" class="w-full h-full cursor-pointer" />
                    </div>
                    <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                        <img src="{{ asset('assets/images/hero/4.png')}}" alt="face of a bamboon" class="w-full h-full cursor-pointer" />
                    </div>
                    <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                        <img src="{{ asset('assets/images/hero/5.png')}}" alt="xray of a hand" class="w-full h-full cursor-pointer hidden md:block" />
                    </div>
                </div>
            </section>

            <section id="benefits" class="flex justify-center text-white pt-32">
                <div class="px-6 w-full lg:w-6/12 lg:px-0">
                    <h2 class="text-center text-4xl tracking-widest">THE FUTURE OF EDUCATION</h2>
                    <p class="text-center tracking-widest leading-7 py-6">
                        Discover Venture, where students navigate their unique path through knowledge,
                        transcending traditional limits for a deeply personalized experience. Here, curiosity
                        guides learning, sparking a lifelong passion and independent thinking tailored to
                        each learner. Join us in shaping innovative thinkers and pioneers in an exciting,
                        curiosity-led educational future.
                    </p>
                </div>
            </section>
        </div>

        <section id="subjects" class="w-full bg-gradient-to-t from-black to-transparent h-[400px] lg:h-[800px]">
            <div class="flex justify-center">
                <div class="hidden relative w-full lg:block">
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:15%;top:170px;width:196px;height:21px;">
                        <img src="{{ asset('assets/images/icons/20.png') }}" alt="conservation" class="w-5 h-5" />
                        Conservation
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:26%;top:200px;width:126px;height:21px;">
                        <img src="{{ asset('assets/images/icons/5.png') }}" alt="astronomy" class="w-5 h-5" />
                        Astronomy
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:10%;top:250px;width:196px;height:21px;">
                        <img src="{{ asset('assets/images/icons/25.png') }}" alt="legal-studies" class="w-5 h-5" />
                        Legal Studies
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:45%;top:209px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/20.png') }}" alt="geography" class="w-5 h-5" />
                        Geography
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:67%;top:108px;width:196px;height:21px;">
                        <img src="{{ asset('assets/images/icons/8.png') }}" alt="coding" class="w-5 h-5" />
                        Coding
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:31%;top:130px;width:330px;height:21px;">
                        <img src="{{ asset('assets/images/icons/13.png') }}" alt="entrepreneurship" class="w-5 h-5" />
                        Entrepreneurship
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:23%;top:300px;width:126px;height:21px;">
                        <img src="{{ asset('assets/images/icons/1.png') }}" alt="science" class="w-5 h-5" />
                        Science
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:34%;top:275px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/6.png') }}" alt="space-eploration" class="w-5 h-5" />
                        Space Eploration
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:10%;top:374px;width:126px;height:21px;">
                        <img src="{{ asset('assets/images/icons/2.png') }}" alt="language-arts" class="w-5 h-5" />
                        Language Arts
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:26%;top:480px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/2.png') }}" alt="history" class="w-5 h-5" />
                        History
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:18%;top:500px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/10.png') }}" alt="literature" class="w-5 h-5" />
                        Literarture
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:83%;top:460px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/28.png') }}" alt="vocational-skills" class="w-5 h-5" />
                        Vocational Skills
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:30%;top:632px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/26.png') }}" alt="mythology" class="w-5 h-5" />
                        Mythology
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:20%;top:600px;width:330px;height:21px">
                        <img src="{{ asset('assets/images/icons/14.png') }}" alt="business" class="w-5 h-5" />
                        Business
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:11%;top:536px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/15.png') }}" alt="robotics" class="w-5 h-5" />
                        Robotics
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:30%;top:530px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/4.png') }}" alt="physical-education" class="w-5 h-5" />
                        Physical Education
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:80%;top:545px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/31.png') }}" alt="ancient-cultures" class="w-5 h-5" />
                        Ancient Cultures
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:45%;top:516px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/30.png') }}" alt="social-studies" class="w-5 h-5" />
                        Social Studies
                    </div>
                    <div class="absolute text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:53%;top:590px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/24.png') }}" alt="music" class="w-5 h-5" />
                        Music
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:40%;top:640px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/10.png') }}" alt="civics-and-government" class="w-5 h-5" />
                        Civics and Government
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:72%;top:645px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/20.png') }}" alt="environmental-studies" class="w-5 h-5" />
                        Environmental Studies
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:62%;top:602px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/32.png') }}" alt="wildlife" class="w-5 h-5" />
                        Wildlife
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:60%;top:481px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/33.png') }}" alt="paleontology" class="w-5 h-5" />
                        Paleontology
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:68%;top:542px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/2.png') }}" alt="storytelling" class="w-5 h-5" />
                        Storytelling
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:74%;top:310px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/4.png') }}" alt="health" class="w-5 h-5" />
                        Health
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:74%;top:480px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/11.png') }}" alt="art" class="w-5 h-5" />
                        Art
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:61%;top:270px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/17.png') }}" alt="archaeology" class="w-5 h-5" />
                        Archaeology
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:54%;top:130px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/2.png') }}" alt="creative-writing" class="w-5 h-5" />
                        Creative Writing
                    </div>
                    <div class="flex absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                        data-taos-offset="100" style="left:74%;top:184px;width:196px;height:21px">
                        <img src="{{ asset('assets/images/icons/34.png') }}" alt="computer-science" class="w-5 h-5" />
                        Computer Science
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:83%;top:299px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/19.png') }}" alt="mathematics" class="w-5 h-5" />
                        Mathematics
                    </div>
                    <div class="absolute text-sm text-neutral-500 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0 hidden lg:flex"
                        data-taos-offset="100" style="left:42%;top:108px;width:126px;height:21px">
                        <img src="{{ asset('assets/images/icons/21.png') }}" alt="marine-biology" class="w-5 h-5" />
                        Marine Biology
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="w-full text-center mt-40 lg:mt-80 lg:w-9/12">
                    <h2 class="text-3xl font-light tracking-widest leading-relaxed text-white md:text-5xl lg:text-6xl xl:text-7xl">
                        LEARN ANYTHING,</h2>
                    <h2 class="text-3xl font-light tracking-widest leading-relaxed text-white md:text-5xl lg:text-6xl xl:text-7xl">
                        ANYTIME, <span class="text-primary-yellow font-semibold">ANYWHERE</span></h2>
                </div>
            </div>
        </section>

        <section class="h-[700px] lg:h-[600px]">
            <div id="grades" class="relative h-full bg-cover bg-center bg-fixed">
                <div class="absolute inset-0 bg-black opacity-50 h-full z-10"></div>
                <div class="max-w-xl mx-auto">
                    <div class="absolute flex space-x-4 top-14 max-w-4xl mx-auto z-10 px-6 lg:space-x-24 lg:px-0">
                        <div id="grade-lower" class="text-white text-center space-y-1 cursor-pointer">
                            <p class="text-xs tracking-wider">GRADES K-4</p>
                            <p class="tracking-widest">LOWER SCHOOL</p>
                        </div>

                        <div id="grade-middle" class="text-white text-center space-y-1 cursor-pointer">
                            <p class="text-xs tracking-wider">GRADES 5-8</p>
                            <p class="tracking-widest">MIDDLE SCHOOL</p>
                        </div>
                        <div id="grade-high" class="text-white text-center space-y-1 cursor-pointer">
                            <p class="text-xs tracking-wider">GRADES 9-12</p>
                            <p class="tracking-widest">HIGH SCHOOL</p>
                        </div>
                    </div>

                    <div id="lower-school"
                        class="absolute text-white z-10 right-50 top-48 space-y-2 hidden px-6 md:right-40 md:w-1/2 lg:w-1/3 xl:w-1/4 lg:px-0">
                        <h2 class="text-3xl border-b pb-2">LOWER SCHOOL (K-4)</h2>
                        <p class="text-justify tracking-wider leading-5">
                            In our Lower School program, we lay the groundwork for a transformative educational journey.
                            Tailored for preschool to fourth grade, our curriculum is a vibrant mosaic of discovery and
                            foundational learning. Here, we focus on nurturing the natural inquisitiveness of young
                            learners,
                            enabling them to question, explore, and understand the world around them. This stage is
                            vital
                            for building a robust base for future education. We believe in a parent-led approach at this
                            level, where our platform acts as a guiding tool for parents, equipping them with resources
                            to
                            lead and inspire their children in this exciting phase of learning.
                        </p>
                    </div>

                    <div id="middle-school"
                        class="absolute text-white z-10 right-50 top-48 space-y-2 hidden px-6 md:right-40 md:w-1/2 lg:w-1/3 xl:w-1/4 lg:px-0">
                        <h2 class="text-3xl border-b pb-2">MIDDLE SCHOOL (5-8)</h2>
                        <p class="text-justify tracking-wider leading-5">
                            Our Middle School curriculum is designed to cater to the unique
                            needs of students navigating the transformative years of adolescence.
                            This stage is marked by an increased sense of independence and
                            growing curiosity about the world and oneself. Our educational
                            materials encourage students to engage in personal reflection
                            while exploring complex concepts and ideas. Emphasizing critical
                            thinking and creative problem-solving, the curriculum prepares
                            students for higher levels of academic pursuit, instilling confidence
                            and a sense of self-reliance. This approach not only enriches their
                            academic experience but also lays a solid foundation for personal
                            growth and intellectual curiosity.
                        </p>
                    </div>

                    <div id="high-school"
                        class="absolute text-white z-10 right-50 top-48 space-y-2 hidden px-6 md:right-40 md:w-1/2 lg:w-1/3 xl:w-1/4 lg:px-0">
                        <h2 class="text-3xl border-b pb-2">HIGH SCHOOL (9-12)</h2>
                        <p class="text-justify tracking-wider leading-5">
                            The High School experience is centered around educational freedom and real-world
                            relevancy, diverging from traditional academic rigor. Our approach is tailored
                            to empower students to explore their passions and interests, connecting learning
                            directly with real-life applications. This stage is pivotal in shaping students
                            who are not just prepared for college or careers but are also passionate lifelong
                            learners. We emphasize personalization of the curriculum, allowing students to
                            delve into subjects that genuinely captivate them. Our platform supports this journey
                            of exploration, offering resources that nurture independence, self-motivation, and
                            practical skills. By focusing on real-world interests and flexible learning paths,
                            we aim to create an environment where students feel empowered to shape their educational
                            experiences, preparing them to meet the future with confidence.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="text-center pt-20 space-y-2">
            <p class="text-white font-semibold tracking-wider">ENROLL NOW</p>
            <div class="text-3xl text-white max-w-5xl mx-auto leading-snug lg:text-4xl">
                <p class="font-extralight">Take advantage of our limited-time beta pricing! Experience <span
                        class="text-primary-yellow font-semibold">Venture</span> for a full month at a special price of
                    only $20.00. Sign up now and start your ad<span
                        class="text-primary-yellow font-semibold">venture</span> today!</p>
            </div>
            <div class="flex justify-center py-8">
                <a href="/register"
                    class="inline-flex items-center px-10 py-4 bg-primary-yellow border border-transparent rounded-md font-semibold text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-dark-gray focus:ring-offset-2 dark:focus:ring-offset-primary-dark-gray transition ease-in-out duration-150">START
                    A TRIAL</a>
            </div>
        </section>

        <section class="mt-20">
            <h2 class="text-center text-4xl text-white font-thin lg:text-7xl">WHAT WILL YOU <span
                    class="text-primary-yellow font-normal">DISCOVER?</span></h2>
            <div class="mt-10 grid grid-cols-2 gap-5 lg:grid-cols-5">
                <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                    <img src="{{ asset('assets/images/15.png') }}" alt="close up of an eye" class="w-full h-full cursor-pointer" />
                </div>
                <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                    <img src="{{ asset('assets/images/17.png') }}" alt="snow covered mountains" class="w-full h-full cursor-pointer" />
                </div>
                <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                    <img src="{{ asset('assets/images/19.png') }}" alt="earth from space" class="w-full h-full cursor-pointer" />
                </div>
                <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                    <img src="{{ asset('assets/images/20.png') }}" alt="man with falcon" class="w-full h-full cursor-pointer" />
                </div>
                <div onclick="toggleResponse(this)" class="transition ease-in-out delay-150 duration-300 hover:scale-110">
                    <img src="{{ asset('assets/images/18.png') }}" alt="scientist looking through a microscope" class="w-full h-full hidden cursor-pointer lg:block" />
                </div>
            </div>
        </section>

        <section id="reviews">
            <div class="relative isolate pb-32 pt-24 sm:pt-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-xl text-center">
                        <h2 class="text-lg font-semibold leading-8 text-white tracking-widest">REVIEWS</h2>
                        <p class="mt-2 text-3xl font-bold text-primary-yellow tracking-wide sm:text-4xl">We have worked
                            with thousands of amazing people</p>
                    </div>
                    <div
                        class="mx-auto mt-16 grid max-w-2xl grid-cols-1 grid-rows-1 gap-8 text-sm leading-6 text-gray-900 sm:mt-20 sm:grid-cols-2 xl:mx-0 xl:max-w-none xl:grid-flow-col xl:grid-cols-4">
                        <figure
                            class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 sm:col-span-2 xl:col-start-2 xl:row-end-1">
                            <blockquote
                                class="p-6 text-lg font-semibold leading-7 tracking-tight text-gray-900 sm:p-12 sm:text-xl sm:leading-8">
                                <p>“Integer id nunc sit semper purus. Bibendum at lacus ut arcu blandit montes vitae
                                    auctor libero. Hac condimentum dignissim nibh vulputate ut nunc. Amet nibh orci mi
                                    venenatis blandit vel et proin. Non hendrerit in vel ac diam.”</p>
                            </blockquote>
                        </figure>
                        <div class="space-y-8 xl:contents xl:space-y-0">
                            <div class="space-y-8 xl:row-span-2">
                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Laborum quis quam. Dolorum et ut quod quia. Voluptas numquam delectus nihil.
                                            Aut enim doloremque et ipsam.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Leslie Alexander</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <!-- More testimonials... -->
                            </div>
                            <div class="space-y-8 xl:row-start-1">
                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        <div class="space-y-8 xl:contents xl:space-y-0">
                            <div class="space-y-8 xl:row-start-1">
                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Voluptas quos itaque ipsam in voluptatem est. Iste eos blanditiis
                                            repudiandae.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Tom Cook</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="space-y-8 xl:row-span-2">
                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Molestias ea earum quos nostrum doloremque sed. Quaerat quasi aut velit
                                            incidunt excepturi rerum voluptatem minus harum.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Leonard Krasner</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>

                                <figure
                                    class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 delay-[300ms] duration-[600ms] taos:translate-y-[200px] taos:opacity-0"
                                    data-taos-offset="100">
                                    <blockquote class="text-gray-900">
                                        <p>“Aut reprehenderit voluptatem eum asperiores beatae id. Iure molestiae ipsam
                                            ut officia rem nulla blanditiis.”</p>
                                    </blockquote>
                                    <figcaption class="mt-6 flex items-center gap-x-4">
                                        <div>
                                            <div class="font-semibold">Lindsay Walton</div>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="max-w-7xl mx-auto">
            <div class="flex flex-col mt-32 lg:flex-row lg:justify-center">
                <div
                    class="flex justify-center w-full text-white text-5xl font-extralight space-y-1 lg:justify-end lg:pr-20 lg:w-1/4">
                    <div class="text-4xl lg:text-5xl">
                        <p>Frequently Asked Questions</p>
                    </div>
                </div>
                <div class="w-full px-6 mt-4 lg:mt-0 lg:px-0 lg:w-1/2">
                    <ul>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                What exactly is curiosity-led learning?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>

                            <ul class="bg-primary-gray p-4 mt-2 cursor-auto hidden">
                                <li class="text-neutral-400">Curiosity-led learning is an approach that empowers
                                    students to explore and learn based on their unique interests and passions. Instead
                                    of following a fixed curriculum, students have the freedom to choose topics that
                                    genuinely intrigue them, fostering a deep sense of curiosity and motivation.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Is the Venture Curriculum suitable for all ages?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>

                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Yes, the Venture Curriculum is designed to accommodate
                                    homeschoolers of all ages, from Kindergarten to 12th Grade. We recommend a
                                    parent-led approach for the earlier years that progressively becomes more
                                    independent as the years go on.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Can you explain the concept of self-paced progression?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Self-paced progression allows students to learn at their
                                    own speed and depth. They have the flexibility to move through the curriculum
                                    without being rushed, ensuring they thoroughly understand the material before moving
                                    on to the next topic.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How does Venture personalize content for each student?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Venture personalizes content by considering each student's
                                    age, interests, and reading style. This approach ensures that the educational
                                    material is engaging, age-appropriate, and tailored to the individual needs of each
                                    learner.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                What role does literary comprehension play in the curriculum?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Literary comprehension is a fundamental aspect of the
                                    platform. It focuses on developing strong reading and comprehension skills through
                                    engaging texts, enabling students to explore various subjects and enhance their
                                    ability to comprehend written material effectively.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How does the Venture foster interdisciplinary connections?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Venture encourages students to explore how different
                                    subjects are interconnected. By examining the relationships between various
                                    disciplines, students gain a better understanding of the world and its complexities,
                                    promoting interdisciplinary connections.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How can learning progress be tracked by parents?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Parents can track learning progress through the parent
                                    portal and weekly student summary that will give them a detailed breakdown of what
                                    the child has learned.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Is a structured curriculum offered on Venture?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">While Venture embraces a flexible and student-centered
                                    approach, it is also currently working on structured curriculum options. This way
                                    students can choose between structured pathways or explore topics of interest in a
                                    more self-directed manner.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How do you ensure content is child-safe and appropriate?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Content on Venture is carefully curated to ensure it is
                                    age-appropriate and child-safe. The team behind Venture has spent a lot of time
                                    creating safeguards for content to create a safe educational environment for
                                    students.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Which subjects or areas does Venture cover?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Venture covers a wide range of subjects and areas,
                                    including mathematics, sciences, literature, history, arts, physical education, life
                                    skills, and more. The platform is designed to offer a comprehensive and well-rounded
                                    education that covers just about any topics your child is interested in. It's what
                                    makes our platform so different from the rest.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Can parents actively participate in their child's education with Venture?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Yes, parents are encouraged to actively participate in
                                    their child's education on Venture. And we highly recommend joining your child as
                                    they explore their interests. You might even learn something new.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How does Venture complement traditional homeschooling?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Venture complements traditional homeschooling by offering a
                                    flexible and engaging curriculum that can be integrated into existing homeschooling
                                    routines. It provides additional resources and expertise to enhance the
                                    homeschooling experience.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Can Venture be used alongside regular schooling?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Yes, Venture can be used alongside regular schooling as a
                                    supplementary educational resource. It offers opportunities for students to explore
                                    their interests and passions beyond traditional school hours.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                How can users provide feedback to the Venture team?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">Users can provide feedback to the Venture team through
                                    designated channels, such as contact forms, or email. The team welcomes feedback to
                                    continuously improve the curriculum and user experience.</li>
                            </ul>
                        </li>
                        <li onclick="toggleFAQ(this)"
                            class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                            <div class="flex items-center justify-between">
                                Who are the founders behind Venture?
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <ul class="bg-primary-gray p-4 mt-2 hidden">
                                <li class="text-neutral-400">The founders of Venture are a dedicated husband-wife team
                                    with a strong homeschooling background. Elena, the visionary leader, has
                                    successfully built multiple companies, while Jon, a self-taught developer, brings
                                    technical expertise to the Venture. Elena's brother, Kai, has also joined the team,
                                    adding his unique skills. Together, they share a deep commitment to homeschooling
                                    and are passionate about making Venture a valuable resource for homeschooling
                                    families.</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
    <footer class="py-20 max-w-2xl mx-auto">
        <div class="flex items-center">
            <div class="py-3 text-sm text-center text-white px-10 border-r border-primary-yellow">
                <p>CURIOSITY-LED LEARNING</p>
                <p>FOR CREATIVE MINDS,</p>
                <p>ENCOURAGING EXPLORATION</p>
                <p>AND DISCOVERY.</p>
            </div>
            <div class="hidden px-10 border-r border-primary-yellow md:block">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block w-20">
                    <path
                        d="M 239 51.091 C 217.746 55.212, 195.337 80.006, 180.940 115.334 C 178.575 121.137, 178.544 121.165, 175.031 120.588 C 173.089 120.269, 162.725 119.727, 152 119.385 C 135.936 118.871, 130.914 119.060, 123.500 120.458 C 93.398 126.132, 76.179 143.807, 75.662 169.561 C 75.500 177.607, 75.493 177.629, 71.592 181.054 C 62.983 188.612, 60.379 202.348, 65.578 212.780 C 70.298 222.252, 78.230 226.943, 89.588 226.978 L 96.676 227 102.705 234.778 L 108.734 242.556 105.788 246.028 C 100.246 252.559, 88.083 271.540, 84.579 279.123 C 72.778 304.664, 72.705 326.870, 84.370 342.716 C 98.944 362.513, 129.789 370.091, 173.020 364.493 L 178.540 363.779 180.929 369.639 C 195.142 404.512, 215.803 427.807, 237.542 433.469 C 251.856 437.197, 266.496 433.580, 279.869 423.010 C 285.094 418.879, 285.142 418.865, 290.093 419.927 C 299.030 421.843, 309.076 418.234, 315.415 410.828 C 319.031 406.604, 322 399.016, 322 394 C 322 389.592, 319.097 381.269, 316.471 378.148 L 313.991 375.201 316.176 369.493 L 318.360 363.785 322.430 364.374 C 362.093 370.113, 391.441 364.512, 408.080 348.028 C 429.922 326.389, 425.055 290.599, 394.857 250.777 C 391.636 246.529, 389 242.807, 389 242.505 C 389 242.204, 391.192 239.154, 393.872 235.729 C 396.552 232.303, 400.350 226.976, 402.312 223.892 L 405.880 218.283 412.219 217.775 C 419.672 217.176, 425.084 214.636, 430.201 209.335 C 435.227 204.128, 437 199.405, 437 191.228 C 437 181.892, 433.741 175.349, 426.383 169.914 C 422.091 166.743, 421 165.367, 421 163.125 C 421 161.578, 420.087 157.655, 418.971 154.407 C 413.377 138.119, 400.611 127.599, 379.500 121.878 C 372.741 120.046, 368.630 119.694, 353 119.605 C 342.825 119.547, 330.831 119.880, 326.348 120.345 C 318.333 121.177, 318.183 121.151, 317.487 118.845 C 315.702 112.933, 309.296 99.575, 303.671 90.034 C 295.956 76.948, 280.856 61.117, 271.419 56.219 C 260.497 50.550, 250.196 48.921, 239 51.091 M 239 63.878 C 222.467 69.302, 206.528 87.894, 194.685 115.567 L 191.232 123.635 204.866 127.474 C 217.088 130.915, 235.493 137.267, 245 141.324 C 248.277 142.722, 248.882 142.661, 254.500 140.356 C 265.042 136.030, 280.139 130.949, 292.915 127.426 C 299.744 125.543, 305.466 123.867, 305.632 123.701 C 306.269 123.065, 296.748 103.130, 292.236 95.651 C 283.219 80.708, 274.476 71.824, 263 65.946 C 259.560 64.185, 256.498 63.573, 250 63.350 C 245.325 63.189, 240.375 63.427, 239 63.878 M 130.500 132.139 C 115.385 134.053, 103.666 139.241, 96.529 147.178 C 91.615 152.644, 89.182 158.702, 88.326 167.601 L 87.739 173.703 92.077 174.396 C 112.524 177.666, 122.575 199.862, 110.905 215.973 C 109.307 218.178, 108 220.246, 108 220.567 C 108 220.889, 109.996 223.746, 112.436 226.916 L 116.871 232.681 127.686 222.244 C 133.634 216.503, 142.721 208.363, 147.881 204.153 C 153.040 199.944, 158.324 195.600, 159.622 194.500 C 161.563 192.856, 162.245 190.721, 163.459 182.500 C 165.463 168.923, 168.488 154.376, 171.631 143.206 C 173.069 138.095, 174.078 133.749, 173.873 133.550 C 173.103 132.802, 151.376 131.005, 144.576 131.126 C 140.684 131.196, 134.350 131.652, 130.500 132.139 M 332.530 132.118 C 327.597 132.662, 323.413 133.254, 323.232 133.435 C 323.051 133.616, 324.017 137.754, 325.379 142.632 C 328.480 153.739, 332.435 172.810, 333.995 184.175 L 335.185 192.851 341.343 197.612 C 349.831 204.175, 360.846 213.848, 371.359 223.970 L 380.218 232.500 383.272 228.765 C 384.952 226.710, 388.225 222.235, 390.546 218.820 L 394.765 212.612 391.721 209.567 C 384.001 201.848, 382.213 188.892, 387.520 179.141 C 391.247 172.293, 396.034 168.348, 403.208 166.213 C 409.743 164.268, 409.832 163.782, 405.385 154.358 C 400.746 144.527, 389.098 136.664, 374.412 133.451 C 365.601 131.523, 344.134 130.840, 332.530 132.118 M 185.603 140.750 C 182.679 149.663, 175.540 182, 176.496 182 C 176.611 182, 183.039 178.042, 190.782 173.205 C 198.525 168.368, 210.959 161.281, 218.413 157.455 C 225.868 153.630, 231.975 150.229, 231.984 149.898 C 232.016 148.710, 191.686 136.017, 187.830 136.003 C 187.462 136.001, 186.460 138.137, 185.603 140.750 M 297.229 138.946 C 285.174 142.264, 265 149.174, 265 149.985 C 265 150.295, 269.387 152.719, 274.750 155.372 C 285.435 160.659, 305.171 172.065, 314.327 178.246 C 317.532 180.409, 320.332 182.002, 320.548 181.785 C 321.149 181.185, 315.364 153.562, 312.741 144.507 C 311.298 139.524, 309.875 136.439, 308.963 136.316 C 308.158 136.207, 302.878 137.391, 297.229 138.946 M 231 164.848 C 213.851 173.475, 199.715 181.757, 184.315 192.199 L 174.131 199.105 173.315 204.802 C 172.247 212.269, 172.260 272.815, 173.331 280.324 L 174.162 286.147 188.331 295.478 C 204.647 306.223, 225.260 317.947, 239.088 324.346 L 248.675 328.784 260.588 323.069 C 276.660 315.357, 293.710 305.568, 309.663 294.891 C 321.815 286.758, 322.970 285.719, 323.702 282.264 C 324.755 277.299, 324.735 209.876, 323.679 203.683 L 322.857 198.866 308.679 189.527 C 286.432 174.874, 253.126 156.955, 248.266 157.026 C 247.295 157.040, 239.525 160.560, 231 164.848 M 406.643 177.983 C 394.416 181.839, 393.663 198.867, 405.487 204.097 C 414.761 208.200, 424.993 201.592, 424.993 191.500 C 424.993 181.971, 415.700 175.126, 406.643 177.983 M 81.346 189.054 C 73.153 194.303, 73.224 207.169, 81.476 212.576 C 85.306 215.086, 91.186 215.480, 95.345 213.507 C 104.924 208.961, 105.725 194.462, 96.701 188.960 C 92.455 186.370, 85.469 186.413, 81.346 189.054 M 233.762 196.942 C 219.272 201.574, 206.851 214.319, 202.585 228.932 C 200.545 235.923, 200.551 249.110, 202.598 256.068 C 206.969 270.924, 220.100 284.056, 234.932 288.407 C 241.843 290.434, 255.098 290.457, 261.844 288.454 C 282.262 282.390, 296.249 263.622, 296.166 242.400 C 296.084 221.404, 282.665 203.043, 262.909 196.895 C 254.914 194.406, 241.627 194.428, 233.762 196.942 M 238.880 208.316 C 227.805 211.454, 217.604 221.676, 214.339 232.906 C 209.677 248.943, 218.366 267.489, 234 274.871 C 238.651 277.068, 240.889 277.469, 248.500 277.469 C 256.065 277.469, 258.377 277.059, 263.002 274.903 C 270.014 271.634, 277.080 264.574, 280.682 257.239 C 283.154 252.205, 283.495 250.395, 283.459 242.500 C 283.424 234.764, 283.029 232.697, 280.652 227.783 C 277.042 220.322, 270.297 213.483, 263.460 210.353 C 256.792 207.300, 245.706 206.381, 238.880 208.316 M 149.500 218.921 C 144 223.795, 136.223 231.120, 132.217 235.198 L 124.935 242.613 136.217 253.560 C 142.423 259.581, 150.200 266.816, 153.500 269.636 L 159.500 274.765 159.500 242.382 C 159.500 224.572, 159.500 210.013, 159.500 210.029 C 159.500 210.045, 155 214.047, 149.500 218.921 M 337.500 242.474 L 337.500 274.715 346 267.415 C 350.675 263.399, 358.452 256.128, 363.282 251.255 L 372.063 242.397 359.782 230.433 C 353.027 223.853, 345.250 216.616, 342.500 214.351 L 337.500 210.233 337.500 242.474 M 113.514 256.456 C 102.866 269.577, 93.086 288.301, 89.879 301.710 C 82.335 333.252, 100.416 351.824, 140.500 353.706 C 150.209 354.162, 172.586 352.737, 173.742 351.589 C 173.876 351.457, 172.211 344.294, 170.043 335.672 C 167.876 327.050, 165.115 313.738, 163.909 306.088 L 161.715 292.180 150.608 283.188 C 144.498 278.242, 134.392 269.295, 128.148 263.304 L 116.796 252.413 113.514 256.456 M 368.096 264.148 C 361.294 270.656, 351.128 279.630, 345.505 284.090 L 335.282 292.200 333.060 306.350 C 331.838 314.132, 329.073 327.436, 326.916 335.913 C 324.759 344.391, 323.107 351.440, 323.247 351.578 C 324.412 352.735, 346.744 354.164, 356.500 353.706 C 402.078 351.566, 419.181 327.524, 402.918 288.458 C 399.353 279.894, 392.212 267.833, 385.302 258.705 L 380.464 252.314 368.096 264.148 M 177.588 310.472 C 180.314 326.147, 186.390 349.003, 187.830 348.997 C 191.728 348.983, 232 336.291, 232 335.077 C 232 334.733, 227.613 332.281, 222.250 329.628 C 211.629 324.373, 191.846 312.946, 182.805 306.844 C 179.673 304.730, 176.925 303, 176.699 303 C 176.473 303, 176.873 306.362, 177.588 310.472 M 314.500 306.619 C 307.260 311.700, 290.315 321.705, 276.585 329.006 L 264.670 335.342 274.085 338.756 C 287.454 343.604, 309.035 349.369, 310.021 348.356 C 311.639 346.693, 321 308.553, 321 303.624 C 321 302.427, 319.466 303.134, 314.500 306.619 M 242.473 344.661 C 229.700 349.912, 212.195 355.590, 195.299 359.963 L 191.098 361.051 194.617 369.275 C 214.619 416.014, 243.139 433.476, 269.589 415.178 L 274.679 411.658 271.652 405.789 C 266.036 394.900, 268.224 382.547, 277.207 374.432 C 283.080 369.126, 287.283 367.624, 296.079 367.690 C 303.632 367.746, 303.661 367.736, 304.811 364.689 C 305.446 363.008, 305.861 361.539, 305.733 361.426 C 305.605 361.313, 301.088 360.025, 295.695 358.564 C 285.959 355.926, 263.104 348.180, 253.473 344.254 L 248.446 342.205 242.473 344.661 M 287.500 381.988 C 281.271 386.087, 279.231 394.586, 282.995 400.759 C 286.099 405.851, 290.601 408.189, 296.346 407.693 C 309.381 406.569, 313.789 388.720, 302.701 381.960 C 298.474 379.382, 291.440 379.395, 287.500 381.988"
                        stroke="none" fill="#fccc07" fill-rule="evenodd" />
                </svg>
                <p class="text-white tracking-widest">VENTURE</p>
            </div>
            <div class="grid grid-cols-2 gap-3 py-5 px-10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" stroke="currentColor"
                    class="h-6 w-6 text-white">
                    <path
                        d="M288 192v-38.1c0-17.2 3.8-25.9 30.5-25.9H352V64h-55.9c-68.5 0-91.1 31.4-91.1 85.3V192h-45v64h45v192h83V256h56.4l7.6-64h-64z">
                    </path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" stroke="currentColor"
                    class="h-6 w-6 text-white">
                    <path
                        d="M492 109.5c-17.4 7.7-36 12.9-55.6 15.3 20-12 35.4-31 42.6-53.6-18.7 11.1-39.4 19.2-61.5 23.5C399.8 75.8 374.6 64 346.8 64c-53.5 0-96.8 43.4-96.8 96.9 0 7.6.8 15 2.5 22.1-80.5-4-151.9-42.6-199.6-101.3-8.3 14.3-13.1 31-13.1 48.7 0 33.6 17.2 63.3 43.2 80.7-16-.4-31-4.8-44-12.1v1.2c0 47 33.4 86.1 77.7 95-8.1 2.2-16.7 3.4-25.5 3.4-6.2 0-12.3-.6-18.2-1.8 12.3 38.5 48.1 66.5 90.5 67.3-33.1 26-74.9 41.5-120.3 41.5-7.8 0-15.5-.5-23.1-1.4C62.8 432 113.7 448 168.3 448 346.6 448 444 300.3 444 172.2c0-4.2-.1-8.4-.3-12.5C462.6 146 479 129 492 109.5z">
                    </path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" stroke="currentColor"
                    class="h-6 w-6 text-white">
                    <circle cx="256" cy="255.833" r="80"></circle>
                    <path
                        d="M177.805 176.887c21.154-21.154 49.28-32.93 79.195-32.93s58.04 11.838 79.195 32.992c13.422 13.42 23.01 29.55 28.232 47.55H448.5v-113c0-26.51-20.49-47-47-47h-288c-26.51 0-49 20.49-49 47v113h85.072c5.222-18 14.81-34.19 28.233-47.614zM416.5 147.7c0 7.07-5.73 12.8-12.8 12.8h-38.4c-7.07 0-12.8-5.73-12.8-12.8v-38.4c0-7.07 5.73-12.8 12.8-12.8h38.4c7.07 0 12.8 5.73 12.8 12.8v38.4zm-80.305 187.58c-21.154 21.153-49.28 32.678-79.195 32.678s-58.04-11.462-79.195-32.616c-21.115-21.115-32.76-49.842-32.803-78.842H64.5v143c0 26.51 22.49 49 49 49h288c26.51 0 47-22.49 47-49v-143h-79.502c-.043 29-11.687 57.664-32.803 78.78z">
                    </path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" stroke="currentColor"
                    class="h-6 w-6 text-white">
                    <path
                        d="M256 32C132.3 32 32 132.3 32 256c0 91.7 55.2 170.5 134.1 205.2-.6-15.6-.1-34.4 3.9-51.4 4.3-18.2 28.8-122.1 28.8-122.1s-7.2-14.3-7.2-35.4c0-33.2 19.2-58 43.2-58 20.4 0 30.2 15.3 30.2 33.6 0 20.5-13.1 51.1-19.8 79.5-5.6 23.8 11.9 43.1 35.4 43.1 42.4 0 71-54.5 71-119.1 0-49.1-33.1-85.8-93.2-85.8-67.9 0-110.3 50.7-110.3 107.3 0 19.5 5.8 33.3 14.8 43.9 4.1 4.9 4.7 6.9 3.2 12.5-1.1 4.1-3.5 14-4.6 18-1.5 5.7-6.1 7.7-11.2 5.6-31.3-12.8-45.9-47-45.9-85.6 0-63.6 53.7-139.9 160.1-139.9 85.5 0 141.8 61.9 141.8 128.3 0 87.9-48.9 153.5-120.9 153.5-24.2 0-46.9-13.1-54.7-27.9 0 0-13 51.6-15.8 61.6-4.7 17.3-14 34.5-22.5 48 20.1 5.9 41.4 9.2 63.5 9.2 123.7 0 224-100.3 224-224C480 132.3 379.7 32 256 32z">
                    </path>
                </svg>
            </div>
        </div>
    </footer>
    <div id="opaque-background" class="absolute hidden inset-0 bg-black opacity-75 z-20"></div>
    <script src="https://unpkg.com/taos@1.0.5/dist/taos.js"></script>
    <script src="{{ asset('assets/js/welcome.js') }}" defer></script>
</body>

</html>
