<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Http\Requests\StoreBookRequest;
use Modules\Library\Http\Requests\UpdateBookRequest;
use Modules\Library\Http\Resources\BookResource;
use Modules\Library\Repositories\Interfaces\BookRepositoryInterface;

class BookController extends Controller
{
    protected $bookRepo;

    public function __construct(BookRepositoryInterface $bookRepo)
    {
        $this->bookRepo = $bookRepo;
    }

    public function index()
    {
        $books = Book::with(['author','category'])->paginate(10);
        return BookResource::collection($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookRepo->create($request->all());
        return new BookResource($book);
    }

    public function show($id)
    {
        return new BookResource ($this->bookRepo->findById($id));
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $this->bookRepo->update($id, $request->all());
        return new BookResource($book);
    }

    public function destroy($id)
    {
        $this->bookRepo->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
