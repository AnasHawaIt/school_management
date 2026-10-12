<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Contracts\Services\ReportCardServiceInterface;
use Modules\Examination\Http\Resources\ReportCardResource;

class ReportCardController extends Controller
{
    public function __construct(
        protected ReportCardServiceInterface $reportCardService,
    ) {}

    // GET /report-cards
    public function index(Request $request): JsonResponse
    {
        $result = $this->reportCardService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => ReportCardResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // GET /report-cards/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ReportCardResource($this->reportCardService->getReportCard($id)),
        ]);
    }

    // GET /students/{student}/report-card?semester_id=1
    public function studentReportCard(Request $request, int $studentId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        try {
            $card = $this->reportCardService->getStudentReportCard($studentId, $request->semester_id);
            return response()->json(['success' => true, 'data' => new ReportCardResource($card)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    // GET /sections/{section}/report-cards?semester_id=1
    public function sectionReportCards(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $cards = $this->reportCardService->getSectionReportCards($sectionId, $request->semester_id);
        return response()->json([
            'success' => true,
            'data'    => ReportCardResource::collection($cards),
        ]);
    }

    // POST /sections/{section}/report-cards/generate?semester_id=1
    public function generate(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $result = $this->reportCardService->generateForSection($sectionId, $request->semester_id);
        return response()->json([
            'success' => true,
            'message' => "{$result['generated']} report cards generated successfully.",
            'data'    => $result,
        ]);
    }

    // PATCH /report-cards/{id}/publish
    public function publish(int $id): JsonResponse
    {
        $card = $this->reportCardService->publish($id);
        return response()->json([
            'success' => true,
            'message' => 'Report card published.',
            'data'    => new ReportCardResource($card),
        ]);
    }

    // POST /sections/{section}/report-cards/publish-all?semester_id=1
    public function publishAll(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $result = $this->reportCardService->publishAll($sectionId, $request->semester_id);
        return response()->json([
            'success' => true,
            'message' => "{$result['published']} report cards published.",
            'data'    => $result,
        ]);
    }

    // PUT /report-cards/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'teacher_remarks' => 'nullable|string|max:1000',
        ]);
        $card = $this->reportCardService->getReportCard($id);
        $card->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Report card updated.',
            'data'    => new ReportCardResource($card->fresh()),
        ]);
    }
}
