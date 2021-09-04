<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function storeToModel(Request $request, $fileKey = 'images', $fileType = 'image', $fileNameExtension = '_image.')
    {
        $this->validate($request, [
            $fileKey . '.*' => 'nullable|sometimes|mimes:jpg,jpeg,png,bmp',
        ]);

        $files = $request->file($fileKey);

        if (is_null($files)) {
            return [];
        }
        if (!is_array($files)) {
            $files = [$files];
        }
        if ( !is_dir( public_path('/uploads') ) ) {
            mkdir(public_path('/uploads'), 0777);
        }
        if ( !is_dir( public_path('/uploads/thumbnails') ) ) {
            mkdir(public_path('/uploads/thumbnails'), 0777);
        }
        foreach($files as $key=>$singleFile)
        {
            $fileName = Carbon::now()->format('Y-m-d') . "-" . uniqid() . $fileNameExtension . $singleFile->getClientOriginalExtension();
            Storage::disk('uploads')->put( $fileName, file_get_contents($singleFile) );

            Image::make( $singleFile->path() )->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save( Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix() . 'thumbnails/' . $fileName);

            $attachments[] = new Attachment([
                'name' => $fileName,
                'file_type' => $fileType,
                'url' => Storage::disk('uploads')->url($fileName),
                'thumbnail_url' => Storage::disk('uploads')->url('thumbnails/' . $fileName)
            ]);
        }
        return $attachments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Attachment $image
     * @return void
     */
    public function show(Attachment $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attachment $image
     * @return void
     */
    public function edit(Attachment $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Attachment $image
     * @return void
     */
    public function update(Request $request, Attachment $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attachment $attachment
     * @return bool
     * @throws \Exception
     */
    public function delete(Attachment $attachment)
    {
        Storage::disk('uploads')->delete($attachment->name);
        Storage::disk('uploads')->delete('thumbnails/' . $attachment->name);

        return $attachment->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attachment $attac
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Attachment $attachment)
    {
        $result = $this->delete($attachment);
        return ( $result )
            ? response()->json([
                ['message'=>__('Attachment deleted'), 'class' => 'is-success']
            ])
            : response()->json([
                ['message'=>__('Attachment not deleted'), 'class' => 'is-danger']
            ]);
    }
}
