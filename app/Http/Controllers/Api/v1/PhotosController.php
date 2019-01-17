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
        $photo->has_cropped = true;
        $photo->save();

        $image = new \claviska\SimpleImage();
        $image->fromFile($request->file)
            ->resize(240, null)
            ->toFile(storage_path('app/public/img/users/') . $photo->filename_cropped, 'image/jpeg', 60);

        return $photo;
    }

    public function destroy($id)
    {
        Photo::find($id)->delete();
    }
}
