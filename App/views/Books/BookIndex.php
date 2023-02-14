

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>
    <a href="/create">Maak boek</a>
    <table>
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
                <td><a href="/<?= $book->id ?>">Show</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>