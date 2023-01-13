<?php

namespace App\Http\Controllers\Api;

use App\Filter\ApplicationFilters;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ModelResource;
use App\Models\Application;
use App\Models\Attachment;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class ApiApplicationController extends Controller
{
    private ApplicationService $applicationService;

    public function __construct(protected AttachmentController $AttachmentController)
    {
        $this->middleware(['check_legal', 'check_child_owner_legal']);
        $this->applicationService = new ApplicationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters, $status = null)
    {
        $result = $this->applicationService->index($request, $filters, $status == 0 ? null : $status);
        extract($result);
        $applications =  ApplicationResource::collection($applications);
        return compact('title', 'applications', 'totals');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Application $application)
    {
        $this->authorize('create', Application::class);
        return $this->applicationService->userDatasForNewApplication($application);
    }

    public function edit(Application $application)
    {
        $this->authorize('update', $application);
        return $this->applicationService->edit($application);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        $this->authorize('create', Application::class);
        return $this->applicationService->store($request, $this->AttachmentController);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        list($application, $isUpdate) = $this->applicationService->update($request, $application);

        if ($isUpdate) {
            return response()->json(['message' => __('Updated.'), 'status_id' => $application->status->id], 200);
        }
        return response()->json(['message' => __('Error.')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addPhotos(Request $request, Application $application)
    {
        $fileKey = 'images';
        $fileType = 'image'; $fileNameExtension = '_image.';
//        $this->validate($request, [
//            $fileKey . '.*' => 'nullable|sometimes|mimes:jpg,jpeg,png,bmp',
//        ]);

        $files = $request->file($fileKey);


        if ($fileKey == "imagespopup") {
            $files = $request->all();
        }

        if (is_null($files)) {
            return [];
        }
        if (!is_array($files)) {
            $files = [$files];
        }
        if (!is_dir(public_path('/uploads'))) {
            mkdir(public_path('/uploads'), 0777);
        }
        if (!is_dir(public_path('/uploads/thumbnails'))) {
            mkdir(public_path('/uploads/thumbnails'), 0777);
        }

//        return collect($files)->first();
        foreach ($files as $key => $singleFile) {
            $fileName = uniqid() . $fileNameExtension . '^' . $singleFile->getClientOriginalName();
//            return $singleFile;

            Storage::disk('uploads')->put($fileName, file_get_contents($singleFile));

            Image::make($singleFile->path())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix() . 'thumbnails/' . $fileName);

            $attachments[] = new Attachment([
                'name' => $fileName,
                'file_type' => $fileType,
                'url' => Storage::disk('uploads')->url($fileName),
                'thumbnail_url' => Storage::disk('uploads')->url('thumbnails/' . $fileName)
            ]);
        }


        if (count($attachments ) > 0) {
            $application->attachments()->saveMany($attachments);
        }
        return response($application, Response::HTTP_CREATED);
        if (count($attachments = $this->AttachmentController->storeToModel($request, 'images')) > 0) {
            $application->attachments()->saveMany($attachments);
        }
        return response($application, Response::HTTP_CREATED);
    }

    public function checkDuplicate(Request $request)
    {
        if ($request->has('vin')) {
            $request->request->replace(['vin' => [$request->get('vin')]]);
        }
        list($licensePlateDuplicates, $vinDuplicates) = $this->applicationService->checkApplicationDuplicate($request);

        return response()->json([
            'license_plates' => ModelResource::collection(json_decode(json_encode($licensePlateDuplicates)), 'status'),
            'vins' => ModelResource::collection(json_decode(json_encode($vinDuplicates)), 'status'),
//            'license_plates' => $licensePlateDuplicates,
//            'vins' => $vinDuplicates,
        ], 200);
    }

    public function getModelChatContent(Request $request, $application_id)
    {
        if ($request->has('notification') && $application = Application::find($application_id)) {
            $notification = $application->notifications()->find($request->notification);
            if ($notification) {
                $notification->markAsRead();
            }
        }
        return $this->getModelContent($request, $application_id);
    }

    public function getModelContent(Request $request, $application_id)
    {
        if (($htmlRender = $this->applicationService->renderModal($request, $application_id)) == null) {
            return response()->noContent();
        }
        $application = [];
        extract($htmlRender);
        $application = new ApplicationResource($application);
        return response()->json(compact('application', 'partnerNotifications', 'storageNotifications'), 200);
    }
}
