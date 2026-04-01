<?php

namespace Modules\Announcement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Announcement\Services\AnnouncementService;
use Modules\Announcement\app\Requests\AnnouncementCreateRequest;
use Modules\Announcement\app\Requests\AnnouncementUpdateRequest;
use Modules\Announcement\app\Requests\AnnouncementDeleteRequest;
use Modules\SMS\Jobs\SendSmsJob;

class AnnouncementController extends Controller
{
    protected AnnouncementService $service;

    public function __construct(AnnouncementService $service)
    {
        $this->service = $service;

        // يمكنك تطبيق middleware هنا
        // مثال: $this->middleware('auth');
    }

//    public function test()
//    {
//        SendSmsJob::dispatch(
//            '963993168007',
//            'اختبار 🚀'
//        );
//
//        return 'done';
//    }

    public function index()
    {
        // استخدام الكاش لمدة 5 دقائق لتقليل استعلامات DB
        $announcements = cache()->remember('announcements_all', 300, function () {
            return $this->service->getAll();
        });

        return response()->json(['announcement::index', compact('announcements')]);
    }

    public function store(AnnouncementCreateRequest $request)
    {
        $announcement = $this->service->create($request->validated());

        return response()->json(['Announcement'=>$announcement, 'Announcement created success']);
    }

    public function update(AnnouncementUpdateRequest $request)
    {
        $announcement = $this->service->update($request->id, $request->validated());

        return response()->json(['Announcement'=>$announcement, 'Announcement updated success'],);
    }

    public function destroy(AnnouncementDeleteRequest $request)
    {
        $this->service->delete($request->id);

        return response()->json(['success', 'Announcement deleted!'],);
    }
}
