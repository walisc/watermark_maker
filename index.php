<?php
// include composer autoload
require 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

// create an image manager instance with favored driver
set_time_limit(300);
$manager = new ImageManager(array('driver' => 'gd'));


$count = 1;
$shrink_factor = 0.15;
$shrink_axis = "y-axis"; //or x-axis

foreach (new DirectoryIterator('pictures/unprocessed') as $pic) {
    if ($pic->isFile()) {
        echo '-------- ' . $count++ . ' -------- ';
        echo '<p>Processing image:- ' . $pic->getFilename() . '</p>';
        $watermark = $manager->make('pictures/logo.png');
        $main_image = $manager->make('pictures/unprocessed/' . $pic->getFilename());

        if ($shrink_axis == "y-axis") {
            $image_height = $main_image->height();
            $watermark_height = $image_height * $shrink_factor;
            $watermark_width = ($watermark->width() * ($image_height * $shrink_factor)) / $watermark->height();
        }
        elseif ($shrink_axis == "x-axis"){
            $image_width = $main_image->width();
            $watermark_width = $image_width * $shrink_factor;
            $watermark_height = ($watermark->height() * ($image_width * $shrink_factor)) / $watermark->width();
        }
        $watermark->resize($watermark_width, $watermark_height);


        $main_image->insert($watermark, 'bottom-left', 10, 10);
        $main_image->save('pictures/processed/' . $pic->getFilename());
        echo '<p>Completed Processing image:- ' . $pic->getFilename(). '</p>';
    }
}






