<?php
    function typesThumbnail($media){
        if(!empty($media)){
            
        $filenamewithextension = $media->getClientOriginalName();
      //get filename without extension
      $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

      //get file extension
      $extension = $media->getClientOriginalExtension();

      //filename to store
      $filenametostore = $filename.'_'.time().'.'.$extension;

      //small thumbnail name
      $smallthumbnail = $filename.'_'.time().'(S)'.'.'.$extension;

      //medium thumbnail name
      $mediumthumbnail = $filename.'_'.time().'(M)'.'.'.$extension;

      //large thumbnail name
      $largethumbnail = $filename.'_'.time().'(L)'.'.'.$extension;
       return [
        'filenametostore'=>$filenametostore,
        'smallthumbnail'=>$smallthumbnail,
        'mediumthumbnail'=>$mediumthumbnail,
        'largethumbnail'=>$largethumbnail
       ];
      
        }
    }
//}

function uploadFile($media,$namefolder,$filenametostore,$smallthumbnail,$mediumthumbnail,$largethumbnail) {
  $file_path_original =$media->storeAs('public/'.$namefolder, $filenametostore);
  $file_path_small_thumbnail =$media->storeAs('public/'.$namefolder.'/thumbnail', $smallthumbnail);
  $file_path_medium_thumbnail =$media->storeAs('public/'.$namefolder.'/thumbnail', $mediumthumbnail);
  $file_path_large_thumbnail =$media->storeAs('public/'.$namefolder.'/thumbnail', $largethumbnail);
// $file_path_original =$media->storeAs($namefolder, $filenametostore);
//   $file_path_small_thumbnail =$media->storeAs($namefolder.'/thumbnail', $smallthumbnail);
//   $file_path_medium_thumbnail =$media->storeAs($namefolder.'/thumbnail', $mediumthumbnail);
//   $file_path_large_thumbnail =$media->storeAs($namefolder.'/thumbnail', $largethumbnail);

  return [
    'file_path_original'=>$file_path_original,
    'file_path_small_thumbnail'=>$file_path_small_thumbnail,
    'file_path_medium_thumbnail'=>$file_path_medium_thumbnail,
    'file_path_large_thumbnail'=>$file_path_large_thumbnail
  ];
  
}
/**
 * Create a thumbnail of specified size
 *
 * @param string $path path of thumbnail
 * @param int $width
 * @param int $height
 */
function createThumbnail($path, $width, $height)
{
    
    
   $img= Image::make('public/foo.jpg')->fit(600, 390)->save(public_path('images/blog/' . $filename));
    dd($img);
    $img = Image::make('public/foo.jpg')->resize(320, 240)->insert('public/watermark.png');
   // dd($path);

     $img = Image::make($path)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });
    $img->save($path);
}