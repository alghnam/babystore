<?php
namespace App\GeneralClasses;
use Illuminate\Support\Facades\Storage;
use Image;
class MediaClass {
    public static function store($file,$foldername){
        $resTypesThumbnail=    typesThumbnail($file);
        $filenametostore= $resTypesThumbnail['filenametostore'];
        $smallthumbnail= $resTypesThumbnail['smallthumbnail'];
        $mediumthumbnail= $resTypesThumbnail['mediumthumbnail'];
        $largethumbnail= $resTypesThumbnail['largethumbnail'];
        
        //Upload File
        $resUploadFile = uploadFile($file,$foldername,$filenametostore,$smallthumbnail,$mediumthumbnail,$largethumbnail);
        $file_path_original=$resUploadFile['file_path_original'];
        $resUploadFile['file_path_small_thumbnail'];
        $resUploadFile['file_path_medium_thumbnail'];
        $resUploadFile['file_path_large_thumbnail'];
        //create small thumbnail
        $smallthumbnailpath = public_path('storage/'.$foldername.'/thumbnail/'.$smallthumbnail);
        
        // createThumbnail($smallthumbnailpath, 150, 93);

        //create medium thumbnail
        $mediumthumbnailpath = public_path('storage/'.$foldername.'/thumbnail/'.$mediumthumbnail);

        //  createThumbnail($mediumthumbnailpath, 300, 185);

        //create large thumbnail
        $largethumbnailpath = public_path('storage/'.$foldername.'/thumbnail/'.$largethumbnail);

        //  createThumbnail($largethumbnailpath, 550, 340);
        return $file_path_original;

    }

    public static function delete($files){
        if(gettype($files)=='object'){
            foreach($files as $file){
                Storage::delete($file->filename);
            }
        }else{
            $file=$files;
            Storage::delete($file);

        }

    }


}
