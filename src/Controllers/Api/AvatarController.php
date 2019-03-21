<?php

namespace Baytek\Laravel\Users\Controllers\Api;

use Baytek\Laravel\Users\File;
use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\Controllers\Controller;

use Illuminate\Http\Request;

use App;
use DB;
use ReflectionClass;
use Route;
use Storage;
use View;
use Intervention\Image\Facades\Image;
/**
 * The Content Controller is suppose to act as an abstract class that facilitates
 * rendering and saving of common resource tables.
 *
 * There are three primary models used for all content types:
 *     Content
 *     ContentMeta
 *     ContentRelations
 *
 * Due to this commonality, it makes sense to have a super class which can handle all
 * data storage and relegate all content specific stuff to the sub classes.
 */
class AvatarController extends Controller
{
    protected $sizes = [60, 120, 360];

    /**
     * Store the avatar
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        $this->redirects = false;

        $uploaded = $request->file('file');
        $originalName = $uploaded->getClientOriginalName();

        $path = $uploaded->store('avatars');

        $file = new File([
            'status' => File::EXCLUDED,
            'key' => str_slug($originalName) . '_' . date('Y-m-d'),
            'language' => $request->language,
            'title' => $originalName,
            'content' => $path
        ]);

        $file->save();

        $file->saveMetadata('file', $path);
        $file->saveMetadata('original', $originalName);

        //We only save the meta data for the user when they update the whole profile
        //So it doesn't need to be saved here

        //Create thumbnails
        $this->createThumbnails($file);

        return response()->json([
            'status' => 'success',
            'file' => $file
        ]);

        return $file;
    }

    /**
     * Create thumbnails
     */
    public function createThumbnails(File $file)
    {
        //Split the path into path/filename and extension
        $path = explode('.', $file->content);

        foreach($this->sizes as $size) {
            Image::make(storage_path('app/'.$file->content))
                ->fit($size)
                ->save(storage_path('app/'.$path[0].'_'.$size.'.'.$path[1]));
        }
    }

    /**
     * Update an existing avatar
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {

    }

    /**
     * Delete an avatar
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id = null)
    {
        $file = File::find($request->id);
        Storage::delete($file->content);

        //Delete the thumbnails as well
        $path = explode('.', $file->content);
        foreach ($this->sizes as $size) {
            Storage::delete($path[0].'_'.$size.'.'.$path[1]);
        }

        $file->delete();

        // dd(File::find($request->id));
        return response()->json([
            'status' => 'success',
            'message' =>  ___('Deleted Successfully'),
        ]);
    }

    /**
     * Show the avatar
     */
    public function show($avatar)
    {
        $file = File::find($avatar);

        return response()->file(storage_path('app/' . $file->content));
    }

    /**
     * Show a thumbnail of the avatar
     */
    public function showThumbnail($avatar, $size)
    {
        if (!in_array($size, $this->sizes)) {
            $this->show($avatar);
        }

        $file = File::find($avatar);
        $path = explode('.', $file->content);

        return response()->file(storage_path('app/'.$path[0].'_'.$size.'.'.$path[1]));
    }

}
