<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ImageDimensions implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $minWidth;
    protected $maxWidth;
    protected $minHeight;
    protected $maxHeight;

    public function __construct(int $minWidth, int $maxWidth, int $minHeight, int $maxHeight)
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
  
    public function passes($attribute, $value)
    {
        if (!($value instanceof UploadedFile)) {
            return false;
        }

        $path = $value->getPathname();

        if (!$path || !file_exists($path)) {
            return false;
        }

        [$width, $height] = getimagesize($path);

        return $width >= $this->minWidth && $width <= $this->maxWidth
            && $height >= $this->minHeight && $height <= $this->maxHeight;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */

    public function message()
    {
        return __('The image dimensions must be between') . " {$this->minWidth}x{$this->minHeight} " . __('and') . " {$this->maxWidth}x{$this->maxHeight} " . __('pixels');
    }
}
