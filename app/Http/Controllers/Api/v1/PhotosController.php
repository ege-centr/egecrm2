<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function upload(Request $request)
    {
        $model = getMorphModel($request->class, $request->entity_id);

        // @todo: delete actual file
        $model->photo()->delete();
        $photo = $model->photo()->create();

        $request->file->storeAs(Photo::UPLOAD_PATH, $photo->filename_original);

        return $photo;
    }

    public function crop(Request $request)
    {
        $photo = Photo::find($request->photo_id);
        $request->file->storeAs(Photo::UPLOAD_PATH, $photo->filename_cropped);
        $photo->has_cropped = true;
        $photo->save();
        return $photo;
    }
}
