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