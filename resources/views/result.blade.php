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

            <div class="content">
                <div class="title m-b-md">

                    <h1>Resultado</h1>

                    <table border=1>
                        <tr>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Peso</th>
                            <th>Codigo</th>
                            <th>Valor Economico</th>
                            <th>Valor Expresso</th>
                        </tr>
                        <tr>
                            <td><?php echo $origin; ?></td>
                            <td><?php echo $destiny; ?></td>
                            <td><?php echo $peso; ?></td>
                            <td><?php echo $code; ?></td>
                            <td><?php echo $priceEco; ?></td>
                            <td><?php echo $priceExp; ?></td>
                        </tr>

                    </table>

                    <div id="map">
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
