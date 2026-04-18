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
        $this->service->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return BookResource::collection(
            $this->service->getBookOnlyTrashed()
        );

    }

    public function index()
    {
        return BookResource::collection(
            Book::with(['author','category'])->paginate(10)
        );
    }

    public function store(StoreBookRequest $request)
    {
        $data =[
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'isbn' => $request->isbn,
            'copies' => $request->copies,
        ];

        $images = $request->file('images');


        return new BookResource($this->service->create($data,$images));
    }

    public function show($id)
    {
        return new BookResource ($this->service->find($id));
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $data =[
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'isbn' => $request->isbn,
            'copies' => $request->copies,
        ];

        $images = $request->file('images');

        $book = $this->service->update($id,$data,$images);

        return new BookResource($book);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
