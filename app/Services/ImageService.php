<?php

namespace App\Services;

use App\Models\Images;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function getAll($model)
    {
        return $model->images->map(function ($image) {
            return [
                'id' => $image->id,
                'path' => $image->path,
                'url' => asset('storage/' . $image->path),
            ];
        });
    }


    public function find(int $id)
    {
        return Images::findOrFail($id);
    }

    public function upload($model, $images)
    {
        if (!$images || empty($images)) {
            return [];
        }

        if (!is_array($images)) {
            $images = [$images];
        }

        $savedImages = [];

        foreach ($images as $image) {
            if (!$image->isValid()) {
                throw new \InvalidArgumentException('Invalid image upload.');
            }

            $path = $image->store('images', 'public');

            $savedImages[] = $model->images()->create([
                'path' => $path
            ]);

        }

        return $savedImages;
    }

    public function deleteAll($model)
    {
        foreach ($model->images as $image)
        {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
    }

    public function delete($imageId)
    {
        $image = Images::findOrFail($imageId);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        return $image->delete();
    }

    public function update($imageId, $newImage)
    {
        $image = Images::findOrFail($imageId);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $path = $newImage->store('images', 'public');

        $image->update([
            'path' => $path
        ]);

        return $image;
    }

    public function replace($model, $images)
    {
        $this->deleteAll($model);

        return $this->upload($model, $images);
    }
}
