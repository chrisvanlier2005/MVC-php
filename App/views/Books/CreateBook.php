
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Toevoegen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="public/app.css">

</head>
<body class="table-max-width py-4 px-2">
<h1>Boek toevoegen</h1>
<form action="<?= route("") ?>" method="post">
    <div class="mb-3">
        <label for="title" class="form-label" id="title-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Titel">
    </div>
    <div class="mb-3">
        <label for="author">Author</label>
        <input type="text" id="author" name="author" class="form-control" placeholder="Author">
    </div>
    <div class="mb-3">
        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" class="form-control" placeholder="ISBN">
    </div>
    <?php if (isset($_GET["error"]) && $_GET["error"] === "empty"): ?>
        <p style="color: red">Please fill in all fields.</p>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Create new.</button>
</form>

</body>
</html>