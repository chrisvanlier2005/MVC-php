
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="public/app.css">
</head>
<body class="table-max-width py-3 px-2">
    <h1>Boeken</h1>
    <a href="<?= route('create')?>" class="btn btn-outline-primary">Maak boek</a>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book->id ?></td>
                <td><?= $book->title ?></td>
                <td><?= $book->author?></td>
                <td><?= $book->isbn ?></td>
                <td><a href="<?= route($book->id) ?>" class="btn btn-primary">Show</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>