<?php

namespace Modules\Messagings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Images;
use Illuminate\Http\Request;
use Modules\Messagings\app\Requests\ForwardMessageRequest;
use Modules\Messagings\app\Requests\ReplyMessageRequest;
use Modules\Messagings\app\Requests\SendMessageRequest;
use Modules\Messagings\app\Resources\InboxResource;
use Modules\Messagings\app\Resources\MessageDetailsResource;
use Modules\Messagings\app\Resources\MessageResource;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Services\MessageService;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;

    }

    public function indexAttachment()
    {
        $iamges = Images::query()->get();

        if (!$iamges) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found Any iamges '
            ], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $iamges->count(),
            'iamgess' => $iamges
        ], 200);
    }

//    public function store(Request $request, $id)
//    {
//        $apartment = Apartment::find($id);
//
//        if (!$apartment) {
//            return response()->json(['message' => 'Apartment not found'], 404);
//        }
//
//        if (auth()->id()!= $apartment->owner_id) {
//            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
//        }
//
//        $request->validate([
//            'image_url'   => 'nullable',
//            'image_url.*' => 'image|mimes:jpg,png,jpeg|max:2048'
//        ]);
//
//        if (!$request->hasFile('image_url')) {
//            return response()->json(['message' => 'No images uploaded'], 400);
//        }
//
//        $files = $request->file('image_url');
//
//        if (!is_array($files)) {
//            $files = [$files];
//        }
//
//        foreach ($files as $img) {
//
//            $imageName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
//            $img->move(public_path('uploads/apartments'), $imageName);
//
//            $image=ApartmentImage::create([
//                'apartment_id' => $apartment->id,
//                'image_url'    => asset('uploads/apartments/' .$imageName)
//            ]);
//        }
//
//        return response()->json(['status'=>true,
//            'image'=>$image,
//            'message' => 'Images stored successfully'], 201);
//    }

//    public function destroy($id){
//
//        $apartment = Apartment::find($id);
//        if (!$apartment) {
//            return response()->json(['message' => 'Apartment not found'], 404);
//        }
//
//        if (auth()->id()!= $apartment->owner_id) {
//            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
//        }
//
//        foreach ($apartment->images as $image) {
//            $apartmentImage = ApartmentImage::find($image->id);
//            $apartmentImage->delete();
//        }
//        return response()->json(['message' => 'Image deleted'], 201);
//    }

    public function uploadAttachment(Request $request, int $message)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $attachment = $this->messageService->uploadAttachment(
            $message,
            $request->file('attachment')
        );

        return response()->json([
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment,
        ], 201);
    }

    public function deleteAttachment($id)
    {
        $this->messageService->deleteAttachment($id);

        return response()->json([
            'message' => 'Attachment deleted successfully.',
        ]);
    }

    public function restore($id)
    {
        return new MessageResource( $this->messageService->restore($id));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->messageService
                ->unreadCount(auth()->id())
        ]);
    }

    public function forceDelete($id)
    {
        $this->messageService->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return MessageResource::collection(
            $this->messageService->getMessageOnlyTrashed()
        );

    }

    public function store(SendMessageRequest $request)
    {
        $message = $this->messageService
            ->send(
                $request->validated()
            );

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    public function  inbox()
    {
        $messages = $this->messageService
                ->getInbox(1);//auth()->id()

        return InboxResource::collection($messages);
    }

    public function sent()
    {
        $messages = $this->messageService
                ->getSent(
                    auth()->id()
                );

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function show(int $id)
    {
        $message = $this->messageService->find($id);

        return new MessageDetailsResource($message);
    }

    public function markAsRead(int $messageId)
    {
        $this->messageService->markAsRead($messageId, auth()->id());

        return response()->json([
            'success' => true
        ]);
    }

    public function reply(int $messageId, ReplyMessageRequest $request)
    {
        $message =
            $this->messageService
                ->reply(
                    $messageId,
                    $request->validated()
                );

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function forward(int $messageId, ForwardMessageRequest $request)
    {
        $message = $this->messageService->forward(
            $messageId,
            $request->validated()['recipients']
        );

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function destroy($id)
    {
        $this->messageService->delete($id);

        return response()->json(['success', 'Message deleted!'],);
    }
}
