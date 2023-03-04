<?php
namespace App\Controllers;
use App\models\Book;
use Core\Controller;
use Exception;

class BookController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): bool
    {
       $books = Book::retrieve()
           ->orderBy("id", "DESC")
           ->get();

       return $this->view('Books.BookIndex', [
           "books" => $books
       ]);
    }

    /**
     * Laat een item zien.
     * @throws Exception
     */
    public function show(string $id): bool
    {
        $book = Book::retrieve()->findOrFail($id);
        return $this->view("Books.BookShow", [
            "book" => $book
        ]);
    }

    /**
     * @throws Exception
     */
    public function create(): bool
    {
        return $this->view("Books.CreateBook");
    }

    /**
     * @throws Exception
     */
    public function store(): bool
    {
        $request = $_POST;
        if (empty($request["title"]) || empty($request["author"]) || empty($request["isbn"])){
            return $this->redirect(route("create") . "?error=empty");
        }
        $book = Book::create($request);
        Book::hydrate($book);
        return $this->redirect(route("{$book->id}"));
    }

    /**
     * @throws Exception
     */
    public function edit(int $id): bool
    {
        $book = Book::retrieve()->findOrFail($id);
        return $this->view("Books.EditBook", [
            "book" => $book
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(int $id){
        $request = $_POST;
        $book = Book::retrieve()->findOrFail($id);
        $book = Book::hydrate($book);

        if (empty($_POST["title"]) || empty($_POST["author"]) || empty($_POST["isbn"])){
            return $this->redirect(route("/$id/edit?error=empty"));
        }

        $book->update([
            "title" => $request["title"],
            "author" => $request["author"],
            "isbn" => $request["isbn"]
        ]);
        return $this->redirect(route(""));
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id){
        $book = Book::retrieve()->findOrFail($id);
        Book::hydrate($book);
        $book->delete();
        return $this->redirect(route(""));
    }

}