<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    public static function handleUploadedImage($file, $path, $delete = null) {
        if ($file) {
            $fullPath = public_path($path);

            // Delete existing file if specified
            if ($delete && file_exists($fullPath . '/' . $delete)) {
                unlink($fullPath . '/' . $delete);
            }

            // Generate unique name and move file
            $name = Str::random(4) . '_' . time() . '_' . $file->getClientOriginalName();
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }
            $file->move($fullPath, $name);

            return $name;
        }
        return null;
    }

    public static function uploadSummernoteImage($file, $path) {
        if ($file) {
            $fullPath = public_path($path);
            $name = Str::random(4) . '_' . time() . '_' . $file->getClientOriginalName();

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $file->move($fullPath, $name);
            return $name;
        }
        return null;
    }
    public static function handleUpdatedUploadedImage($file, $path, $data, $delete_path, $field) {
        // Generate unique file name
        $name = time() . '_' . $file->getClientOriginalName();
    
        // Resolve the full path to the public directory
        $fullPath = public_path($path);
    
        // Ensure the directory exists, create if not
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
    
        // Move the uploaded file to the target directory
        $file->move($fullPath, $name);
    
        // Delete the old file if it exists
        if (!empty($data[$field])) {
            $oldFile = public_path($delete_path . '/' . $data[$field]);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
    
        return $name;
    }
    

    
    public static function ItemhandleUploadedImage($file, $path, $delete = null) {
        if ($file) {
            $fullPath = public_path($path);

            // Delete existing file if specified
            if ($delete && file_exists($fullPath . '/' . $delete)) {
                unlink($fullPath . '/' . $delete);
            }

            // Handle thumbnail generation and main photo upload
            $thumbnailName = Str::random(8) . '.' . $file->getClientOriginalExtension();
            $photoName = time() . '_' . $file->getClientOriginalName();

            // Ensure directory exists
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            // Save resized thumbnail and move main photo
            $image = Image::make($file)->resize(230, 230);
            $image->save($fullPath . '/' . $thumbnailName);
            $file->move($fullPath, $photoName);

            return [$photoName, $thumbnailName];
        }
        return [null, null];
    }

    public static function ItemhandleUpdatedUploadedImage($file, $path, $data, $deletePath, $field) {
        $fullPath = public_path($path);
        $deleteFullPath = public_path($deletePath);

        // Generate new file names
        $photoName = time() . '_' . $file->getClientOriginalName();
        $thumbnailName = Str::random(8) . '.' . $file->getClientOriginalExtension();

        // Ensure directory exists
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        // Save resized thumbnail and move main photo
        $image = Image::make($file)->resize(230, 230);
        $image->save($fullPath . '/' . $thumbnailName);
        $file->move($fullPath, $photoName);

        // Delete existing thumbnail and main photo if they exist
        if (!empty($data['thumbnail']) && file_exists($deleteFullPath . '/' . $data['thumbnail'])) {
            unlink($deleteFullPath . '/' . $data['thumbnail']);
        }
        if (!empty($data[$field]) && file_exists($deleteFullPath . '/' . $data[$field])) {
            unlink($deleteFullPath . '/' . $data[$field]);
        }

        return [$photoName, $thumbnailName];
    }

    public static function handleDeletedImage($data, $field, $deletePath) {
        $deleteFullPath = public_path($deletePath);

        if (!empty($data[$field]) && file_exists($deleteFullPath . '/' . $data[$field])) {
            unlink($deleteFullPath . '/' . $data[$field]);
        }
    }
}
