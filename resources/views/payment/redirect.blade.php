<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>JOBO | PAYMENT</title>
</head>
<body>
    <h1 style="text-align: center">Please, wait...</h1>

    <form action="{{ $params['url'] }}" method="POST" name="redirect">
        @foreach($params as $key => $value)
            @if($key !== 'url')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
            @endif
        @endforeach
    </form>

    <script defer type="text/javascript">
        window.onload = function () {
            document.forms['redirect'].submit();
        }
    </script>
</body>
</html>
