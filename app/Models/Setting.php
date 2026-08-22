<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $guarded = ['id'];

    public static function getSettingsByGroup($type, $group)
    {

        $type = str_replace('_', '-', $type);

        $items = self::where('type', $type)->where('group', $group)->get();

        $settings = [];

        foreach ($items as $data) {
            if (self::_isJson($data['value'])) {
                $settings[$data['key']] = json_decode($data['value'], true);
            } else {
                $settings[$data['key']] = $data['value'];
            }
        }

        return $settings;
    }

    public static function _isJson($text)
    {
        json_decode($text);

        return json_last_error() == JSON_ERROR_NONE;
    }

    public function updatePhoto(UploadedFile $photo, $storagePath = 'settings')
    {
        tap($this->value, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'value' => $photo->storePublicly(
                    $storagePath, ['disk' => $this->photoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->photoDisk())->delete($previous);
            }
        });
    }

    protected function photoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('filesystems.default', 'public');
    }
}
