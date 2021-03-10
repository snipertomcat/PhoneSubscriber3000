<?php

namespace Apithy\Services;

use Apithy\Experience;
use Apithy\Session;
use Apithy\SessionsFiles;
use Apithy\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

/**
 * Class RegisterService
 * @package Apithy\Services
 */
class FileUploadS3Service
{

    public function handleImageUpload(
        Request $request,
        Experience $experience,
        Session $session
    ) {

        //$this->validate($request, UserValidator::avatarRules());

        $file = $request->file("file");
        $original = $file->getFileInfo()->getPathname();

        /* @var \Intervention\Image\Image $image */
        $image = Image::make($original);

        /*
        $image->resize($size['width'], $size['height'], function ($constraint) {
            $constraint->aspectRatio();
        });
        */

        $image->save($original);

        $extension = $file->extension();

        $name = sprintf('%s.%s', $request->get('uuid')."-".time(), $extension);

        $image_path ="experience_media_content";

        $path = $file->storeAs($image_path, $name, ['visibility' => 'public','mimetype'=>$file->getMimeType()]);

        $file_size= $file->getSize();

        if ($path) {
            $request->merge(
                [
                    'filemime' => $file->getMimeType(),
                    'filename' => "$name",
                    'session_id' => $session->id,
                    'size'=>$file_size
                ]
            );

            $session_file = SessionsFiles::create($request->all());
            $session_file->url=$path;
            $session_file->save();
            return $session_file;
        }

        return false;
    }

    public function handleFileUpload(
        Request $request,
        Experience $experience,
        Session $session
    ) {

        //$this->validate($request, UserValidator::avatarRules());

        $file = $request->file("file");/**/
        $original = $file->getFileInfo()->getPathname();
        $extension = $file->getClientOriginalExtension();

        $name = sprintf('%s.%s', $request->get('uuid')."-".time(), $extension);

        $image_path ="experience_media_content";

        $path = $file->storeAs($image_path, $name, ['visibility' => 'public','mimetype'=>$file->getClientMimeType()]);
        $file_size= $file->getSize();

        if ($path) {
            $request->merge(
                [
                    'filemime' => $file->getClientMimeType(),
                    'filename' => "$name",
                    'session_id' => $session->id,
                    'size' => $file_size
                ]
            );

            $session_file = SessionsFiles::create($request->all());
            $session_file->url=$path;
            $session_file->save();
            return $session_file;
        }

        return false;
    }
}
