<?php
namespace SamFramework\Core;

class AppUtils
{
    public static function createFolderIfNotExist($path, $mode = 0777)
    {
        if (!file_exists( $path )) {
            $pathArray = explode( DIRECTORY_SEPARATOR, $path );
            array_pop( $pathArray );
            $parentPath = implode( DIRECTORY_SEPARATOR, $pathArray );
            if (!file_exists( $parentPath )) {
                self::createFolder( $parentPath, $mode );
            }
            mkdir( $path, $mode );
        }
    }
}

