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
         <a href="https://silentridge.io">   <img class = "object-none resize-none mx-auto" src = "https://silentridge.io/assets/logo.php"></a>
    </section>
<!-- Section 1 -->
<section class="px-2 pt-32 bg-white md:px-0">
    <div class="container items-center max-w-6xl px-5 mx-auto space-y-6 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-left text-gray-900 sm:text-5xl md:text-6xl md:text-center">
            <span class="block text-blue-800">{{ __("bankid::messages.title") }}</span>
        </h1>
        <p class="w-full mx-auto text-base text-left text-gray-500 md:max-w-md sm:text-lg lg:text-2xl md:max-w-3xl md:text-center">
            @if(config("bankid.SETUP_COMPLETE") == false)
               {!! trans("bankid::messages.setup") !!}
            @endif
        </p>
        <livewire:bankidcomponent />
    </div>
</section>
@livewireScripts
</body>
</html>
