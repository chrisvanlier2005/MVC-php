

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $book->title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="public/app.css">

</head>
<body class="table-max-width px-2 py-4">
<div>
    <div class="mb-3">
        <a href="<?= route('') ?>" class="btn btn-outline-primary">Terug</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h1 class="card-title"><?= $book->title; ?></h1>
            <p class="card-subtitle">ID: <?= $book->id; ?></p>
            <p>Author: <?= $book->author; ?></p>
            <p>ISBN: <?= $book->isbn; ?></p>
            <div class="d-flex gap-4">
                <a href="<?= route($book->id . '/edit') ?>" class="btn btn-primary">Edit</a>
                <form action="<?=route($book->id)?>" method="post">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>