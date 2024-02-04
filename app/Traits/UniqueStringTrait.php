<?php


namespace App\Traits;


use Carbon\Carbon;
use Illuminate\Support\Str;

trait UniqueStringTrait
{

    /**
     * Generate Unique String
     *
     * @param int $randomStringLength
     * @return string
     */
    public function generateUniqueString($randomStringLength = 16): string
    {
        return Str::random($randomStringLength).Carbon::now()->timestamp;
    }

}
