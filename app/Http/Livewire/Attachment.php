<?php

namespace App\Http\Livewire;

use App\Models\Attachment as AttachmentModel;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class Attachment extends Component
{
    use WithFileUploads;

    public $attachments = [];
    public $images = [];
    public $imageData = [];
    public $fileKey, $fileType, $fileNameExtension;

    public function mount($fileKey = 'images', $fileType = 'image', $fileNameExtension = '_image.')
    {
        $this->fileKey = $fileKey;
        $this->fileType = $fileType;
        $this->fileNameExtension = $fileNameExtension;
    }

    public function updating()
    {
        $this->validate([
            'attachments.*' => 'mimes:jpg,jpeg,png,bmp',
        ]);
    }
    public function updatedAttachments()
    {
        $this->save();
    }

    public function save()
    {

        if ( !is_dir( public_path('/uploads') ) ) {
            mkdir(public_path('/uploads'), 0777);
        }
        if ( !is_dir( public_path('/uploads/thumbnails') ) ) {
            mkdir(public_path('/uploads/thumbnails'), 0777);
        }

        foreach($this->attachments as $singleFile)
        {
            $fileName = Carbon::now()->format('Y-m-d') . "-" . uniqid() . $this->fileNameExtension . $singleFile->getClientOriginalExtension();
            $filePath = $singleFile->storeAs(null, $fileName, 'uploads');

            Image::make( Storage::disk('uploads')->path($filePath) )->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save( Storage::disk('uploads')->path('/') . 'thumbnails/' . $fileName);

            $this->images[] = AttachmentModel::create([
                'name' => $fileName,
                'file_type' => $this->fileType,
                'url' => Storage::disk('uploads')->url($fileName),
                'thumbnail_url' => Storage::disk('uploads')->url('thumbnails/' . $fileName)
            ]);
//            $singleFile->delete();
//            Storage::delete($singleFile->getRealPath());
            $img = end($this->images);
            $this->imageData = [
                'id' => $img->id,
                'url' => $img->thumbnail_url
            ];
            Session::push('images', $this->imageData);
        }
    }


    public function render()
    {
        return view('livewire.attachment');
    }
}
