<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>bewerken</title>
</head>
<body>
<h1>Boek bewerken</h1>
<form action="/<?= $book->id ?>" method="post">
    <input type="hidden" name="_method" value="PUT">
    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= $book->title ?>">
    </div>
    <div>
        <label for="author">Author</label>
        <input type="text" id="author" name="author" value="<?= $book->author ?>">
    </div>
    <div>
        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" value="<?= $book->isbn ?>">
    </div>
    <?php if (isset($_GET["error"]) && $_GET["error"] === "empty"): ?>
        <p style="color: red">Please fill in all fields.</p>
    <?php endif; ?>
    <button type="submit">Save</button>
</form>
</body>
</html>