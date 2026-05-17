<?php
$dir = __DIR__ . '/laravel/public/assets';
$files = scandir($dir);

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
        $source = "$dir/$file";
        $dest = "$dir/" . pathinfo($file, PATHINFO_FILENAME) . '.webp';
        
        $image = imagecreatefrompng($source);
        if ($image) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            $success = imagewebp($image, $dest, 80);
            imagedestroy($image);
            if ($success) {
                echo "Converted $file to WebP\n";
                // Optionally delete original to save space
                // unlink($source);
            } else {
                echo "Failed to convert $file\n";
            }
        }
    }
}
