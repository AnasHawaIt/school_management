<?php

namespace Modules\Library\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Library\app\Http\Requests\StoreBookRequest;
use Modules\Library\app\Http\Requests\UpdateBookRequest;
use Modules\Library\app\Http\Resources\BookResource;
use Modules\Library\Entities\Book;
use Modules\Library\Services\BookService;

class BookController extends Controller
{
    protected $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function restore($id)
    {
        return new BookResource( $this->service->restore($id));
    }

    public function forceDelete($id)
    {
        return new BookResource($this->service->forceDelete($id));
    }

    public function AllOnlyTrashed()
    {
        return new BookResource($this->service->getBookOnlyTrashed());
    }

    public function index()
    {
        $books = Book::with(['author','category'])->paginate(10);
        return new BookResource($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->service->create($request->all());
        return new BookResource($book);
    }

    public function show($id)
    {
        return new BookResource ($this->service->find($id));
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $this->service->update($id, $request->all());
        return new BookResource($book);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
