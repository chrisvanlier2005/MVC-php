

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $book->title ?></title>
</head>
<body>
<div>
    <div>
        <h1><?= $book->title; ?></h1>
        <p><?= $book->id; ?></p>
        <p><?= $book->author; ?></p>
        <p><?= $book->isbn; ?></p>
    </div>
    <div>
        <a href="/<?= $book->id ?>/edit">Edit</a>
        <form action="/<?= $book->id ?>" method="post">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit">Delete</button>
        </form>
    </div>
    <div>
        <a href="/">Terug</a>
    </div>
</div>
</body>
</html>