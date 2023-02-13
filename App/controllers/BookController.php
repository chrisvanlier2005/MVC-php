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
       $books = Book::all();
       return $this->view('Books.BookIndex', [
           "books" => $books
       ]);
    }

    /**
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
    public function store(){
        $request = $_POST;
        Book::create($request);
        return $this->redirect("/");
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
        $book = Book::retrieve()->findOrFail($id);
        Book::hydrate($book);
        $book->update([
            "title" => "test",
            "author" => "chris",
            "isbn" => "123456789"
        ]);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id){
        $book = Book::retrieve()->findOrFail($id);
        $book->delete();
        return $this->redirect("/");
    }

}