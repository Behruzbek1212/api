<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>JOBO | PAYMENT::SUCCESS</title>
    </head>

    <body style="background: whitesmoke">
        <h1 style="text-align: center; color: red;margin-top: 45px">
            Transaction failed
        </h1>

        <p style="text-align: center; color: gray">
            After
            <span data-counter>5</span>
            seconds you will be redirected
        </p>

        <script defer type="text/javascript">
        let counter = document.querySelector('span[data-counter]');
        let seconds = counter.textContent;

        let interval = setInterval(function () {
            seconds -= 1

            if (seconds <= 0) clearInterval(interval)

            counter.textContent = seconds
        }, 1000)

        setTimeout(function () {
            window.location.href = 'https://nuxt.jobo.uz/profile'
        }, 5000)
        </script>
    </body>
</html>
