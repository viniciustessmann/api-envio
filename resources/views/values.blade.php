<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> -->

        <!-- Styles -->
        <style>
            /* html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            } */
        </style>
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

                    <h1>Valores</h1>

                    <table border=1>
                        <tr>
                            <td>Peso minmo</td>
                            <td>Peso maximo</td>
                            <td>l1</td>
                            <td>l2</td>
                            <td>l3</td>
                            <td>l4</td>
                            <td>e1</td>
                            <td>e2</td>
                            <td>e3</td>
                            <td>e4</td>
                            <td>n1</td>
                            <td>n2</td>
                            <td>n3</td>
                            <td>n4</td>
                            <td>n5</td>
                            <td>n6</td>
                            <td>i1</td>
                            <td>i2</td>
                            <td>i3</td>
                            <td>i4</td>
                            <td>i5</td>
                            <td>i6</td>
                        </tr>
                       <?php foreach ($data as  $index => $items) { ?>
                            <tr>
                                <td><?php echo $index; ?></td>
                            </tr>
                            <?php foreach ($items as $index2 => $item) {  ?>
                                <tr>
                                    <?php foreach ($item as $index3 => $obj) {  ?>
                                        <td><?php echo $obj ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                       <?php } ?>
                    </table>
                </div>

                <!-- <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> -->
            </div>
        </div>
    </body>
</html>
