<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class AttachmentController extends AppController
{
    public function upload(Request $request)
    {

        $values = array(
            $request->coordinates[0][0], $request->coordinates[0][1], // Point 2 (x, y)
            $request->coordinates[1][0], $request->coordinates[1][1], // Point 2 (x, y)
            $request->coordinates[2][0], $request->coordinates[2][1], // Point 2 (x, y)
            $request->coordinates[3][0], $request->coordinates[3][1], // Point 2 (x, y)
        );




        header("Content-Type: image/jpg");
        @sleep(1);
        @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
        @ini_set('display_errors', true);
        @ini_set('html_errors', false);
        @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

        define('CMSCORE', true);
        define('ROOT_DIR', substr(dirname(__FILE__), 0, -10));
//        define( 'ENGINE_DIR', ROOT_DIR . '/core' );
        define('UPLOAD_DIR', public_path() . '/uploads/');


        function upload_images_resize_preview($max_width, $max_height, $source_file, $dst_dir, $quality = 90)
        {
            $imgsize = getimagesize($source_file);
            $width = $imgsize[0];
            $height = $imgsize[1];
            $mime = $imgsize['mime'];

            switch ($mime) {
                case 'image/gif':
                    $image_create = "imagecreatefromgif";
                    $image = "imagegif";
                    break;

                case 'image/png':
                    $image_create = "imagecreatefrompng";
                    $image = "imagepng";
                    $quality = 7;
                    break;

                case 'image/jpeg':
                    $image_create = "imagecreatefromjpeg";
                    $image = "imagejpeg";
                    break;

                default:
                    return false;
                    break;
            }

            $dst_img = imagecreatetruecolor($max_width, $max_height);
            ///////////////

            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
            $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
            imagefilledrectangle($dst_img, 0, 0, $max_width, $max_height, $transparent);

            /////////////
            $src_img = $image_create($source_file);

            $width_new = $height * $max_width / $max_height;
            $height_new = $width * $max_height / $max_width;
            //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
            if ($width_new > $width) {
                //cut point by height
                $h_point = (($height - $height_new) / 2);
                //copy image
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
            } else {
                //cut point by width
                $w_point = (($width - $width_new) / 2);
                imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
            }

            $image($dst_img, $dst_dir, $quality);

            if ($dst_img)
                imagedestroy($dst_img);
            if ($src_img)
                imagedestroy($src_img);
        }

//usage example

        function upload_images_random_name($length = 10)
        {
            $string = '';
            $characters = "23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz";

            for ($p = 0; $p < $length; $p++) {
                $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            return $string;
        }

        $response = array();

        $filePath =  $request->filename;

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $new_filename = upload_images_random_name(20) . '.' . $extension;
        $preview_width = 320; //ширина превью
        $preview_height = 240; //высота превью

        $fileNewPath = UPLOAD_DIR . '' . $new_filename;
        $fileNewThumbPath = UPLOAD_DIR . 'thumb_' . $new_filename;


        $fileUrlThumb = $config['site_url'] . 'uploads/thumb_' . $new_filename;

        $mX = intval($request->coordinates[0][0]);
        $mY = intval($_REQUEST['y']);
        $mW = intval($_REQUEST['w']);
        $mH = intval($_REQUEST['h']);

        $imgsize = getimagesize($filePath);
        $mime = $imgsize['mime'];
        $quality = 90;

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image_save = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image_save = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image_save = "imagejpeg";
                break;

            default:
                die();
                break;
        }


        $img1 = $image_create($filePath);

        $x = 200;
        $y = 200;

        $gd = $img1;

        $corners[0] = array(50, 25);
        $corners[1] = array(25, 75);
        $corners[2] = array(100, 10);
        $corners[3] = array(150, 100);



        $red = imagecolorallocate($gd, 255, 0, 0);
//        $red = imagecolorallocatealpha($gd, 255, 255, 255, 127);

//        imagepolygon($gd, $corners, 4, $red);
        imagefilledpolygon($gd, $values, 4, $red);
//        for ($i = 0; $i < 100000; $i++) {
//            imagesetpixel($gd, round($x),round($y), $red);
//            $a = rand(0, 2);
//            $x = ($x + $corners[$a]['x']) / 2;
//            $y = ($y + $corners[$a]['y']) / 2;
//        }

        header('Content-Type: image/png');
//        imagepng($gd);
        $image_save($gd, $fileNewPath, $quality);


        $img2 = imagecreatetruecolor($mW, $mH); // create img2 for selection

        imagecopy($img2, $img1, 0, 0, $mX, $mY, $mW, $mH); // copy selection to img2

        $gaussian = array(
            array(1.0, 2.0, 1.0),
            array(2.0, 4.0, 2.0),
            array(1.0, 2.0, 1.0)
        );
        for ($i = 0; $i <= 100; $i++) {
            if ($i % 5 == 0) {//each 10th time apply 'IMG_FILTER_SMOOTH' with 'level of smoothness' set to -7
                imagefilter($img2, IMG_FILTER_SMOOTH, -7);
            }
            imagefilter($img2, IMG_FILTER_GAUSSIAN_BLUR);
            //imageconvolution($img2, $gaussian, 16, 0); // apply convolution to img2
        }


        imagecopymerge($img1, $img2, $mX, $mY, 0, 0, $mW, $mH, 100); // merge img2 in img1

        $image_save($img1, $fileNewPath, $quality);

        upload_images_resize_preview($preview_width, $preview_height, $fileNewPath, $fileNewThumbPath); //resize

        imagedestroy($img1);
        imagedestroy($img2);

        $response = array('name' => $new_filename, 'url' => $fileUrl, 'thumb_url' => $fileUrlThumb);

        @header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        die();

    }

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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function storeToModelDoc(Request $request, $fileKey = 'docs', $fileType = 'docs', $fileNameExtension = '_doc')
    {
        $this->validate($request, [
            $fileKey . '.*' => 'nullable|sometimes|mimes:doc,docx,xls,xlsx,pdf,csv,jpg,jpeg,png,bmp',
        ]);

        $files = $request->file($fileKey);

        if ($fileKey == "docspopup") {
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
        $thumbnail_url = null;
        foreach ($files as $key => $singleFile) {

            $fileName = uniqid() . $fileNameExtension . '^' . $singleFile->getClientOriginalName();
            Storage::disk('uploads')->put($fileName, file_get_contents($singleFile));
            if (in_array(strtolower($singleFile->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'bmp'])) {
                Image::make($singleFile->path())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix() . 'thumbnails/' . $fileName);
                $thumbnail_url = Storage::disk('uploads')->url('thumbnails/' . $fileName);
            }

            $attachments[] = new Attachment([
                'name' => $fileName,
                'file_type' => $fileType,
                'url' => Storage::disk('uploads')->url($fileName),
                'thumbnail_url' => $thumbnail_url
            ]);
        }

        return $attachments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function storeToModel(Request $request, $fileKey = 'images', $fileType = 'image', $fileNameExtension = '_image.')
    {
        $this->validate($request, [
            $fileKey . '.*' => 'nullable|sometimes|mimes:jpg,jpeg,png,bmp',
        ]);

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

        foreach ($files as $key => $singleFile) {
            $fileName = uniqid() . $fileNameExtension . '^' . $singleFile->getClientOriginalName();


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

        return $attachments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param Attachment $attac
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Attachment $attachment)
    {
        $result = $this->delete($attachment);
        return ($result)
            ? response()->json([
                ['message' => __('Attachment deleted'), 'class' => 'is-success']
            ])
            : response()->json([
                ['message' => __('Attachment not deleted'), 'class' => 'is-danger']
            ]);
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
}
