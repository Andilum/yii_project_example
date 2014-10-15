<?php

class ImageCacheHandlerFile extends ImageCacheHandler {
    public function handle($tmp, $fileData, array $extraData = array()) {
        return $tmp;
    }
}