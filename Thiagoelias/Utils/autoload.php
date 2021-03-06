<?php

//autoloading classes
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);

    # Support for non-namespaced classes.
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));

    $path = implode(DIRECTORY_SEPARATOR, $parts);

    $file = stream_resolve_include_path($path.'.php');
    if($file !== false) {
        require $file;
    }
});
