<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swedish BankId For Laravel 8</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway" media="all">
    @livewireStyles
</head>
<body style="font-family: Raleway">
    <section class="bg-white md:px-0">
<!--
            <img
                class = "object-none resize-none"
                src = "{{asset('vendor/laravel-bankid/logo_2103_full_500px.png')}}"
                srcset = "
                    {{asset('vendor/laravel-bankid/logo_2103_full_1366px.png')}} 1366w,
                    {{asset('vendor/laravel-bankid/logo_2103_full_960px.png')}}  960w,
                    {{asset('vendor/laravel-bankid/logo_2103_full_500px.png')}} 500w,
                    {{asset('vendor/laravel-bankid/logo_2103_full_260x50.png')}} 260w"
                sizes = "(max-width: 300px) 250w,
                    (max-width: 600px) 500w,
                    (max-width: 1160px) 960w,
                    (max-width: 1460px) 1366w,
260w
                "
            >-->


    </section>
<!-- Section 1 -->
<section class="px-2 pt-32 bg-white md:px-0">
    <div class="container items-center max-w-6xl px-5 mx-auto space-y-6 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-left text-gray-900 sm:text-5xl md:text-6xl md:text-center">
            <span class="block">Swedish BankId <span class="block mt-1 text-blue-800 lg:inline lg:mt-0">Configuration</span> test</span>
        </h1>
        <p class="w-full mx-auto text-base text-left text-gray-500 md:max-w-md sm:text-lg lg:text-2xl md:max-w-3xl md:text-center">
            Configure your keys in the bankid config file or your environmnent file. Read more in the documentation.
        </p>
        <livewire:bankidcomponent />
    </div>
</section>
@livewireScripts
</body>
</html>
