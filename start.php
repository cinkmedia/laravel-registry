<?php
Autoloader::namespaces(array(
    'Registry' => Bundle::path('registry'),
));
// Set the global alias for Registry
Autoloader::alias('Registry\\Registry', 'Registry');
Registry::_init();