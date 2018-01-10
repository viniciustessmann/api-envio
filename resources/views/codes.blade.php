<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">

                    <h1>Codes</h1>

                    <table border=1>
                        <!-- <tr>
                            <td>ID</td>
                            <td>State</td>
                        </tr> -->
                        <?php foreach ($codes as $origin => $items) { ?>
                            
                            <?php foreach ($items as $destiny => $item) { ?>
                                <tr>
                                    <td><?php echo $origin; ?></td>
                                    <td><?php echo $destiny; ?></td>
                                    <td><?php echo $item; ?></td>
                                </tr>
                            <?php } ?>
                            
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
