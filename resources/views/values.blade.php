<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Melhor transportadora</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">

                    <h1>Valores</h1>

                    <table border=1>
                        <tr>
                            <td>ID</td>
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
                            <td>Create</td>
                            <td>Update</td>
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
            </div>
        </div>
    </body>
</html>
