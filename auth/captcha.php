<?php
// auth/captcha.php
session_start();

// Generate a random 5-character string (excluding easily confused characters like O, 0, 1, I, l)
$characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
$captcha_text = substr(str_shuffle($characters), 0, 5);

// Store the string in the session for later verification
$_SESSION['captcha'] = $captcha_text;

// Create the image (width: 120px, height: 40px)
$image = imagecreatetruecolor(140, 50);

// Set some colors
$bg_color = imagecolorallocate($image, 240, 240, 240); // Light gray background
$text_color = imagecolorallocate($image, 50, 50, 50);  // Dark gray text
$noise_color = imagecolorallocate($image, 150, 150, 150); // Noise color

// Fill the background
imagefill($image, 0, 0, $bg_color);

// Add random background lines for distortion
for ($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $noise_color);
}

// Add random pixels (dots) for extra noise
for ($i = 0; $i < 100; $i++) {
    imagesetpixel($image, rand(0, 120), rand(0, 40), $noise_color);
}

// Write the string to the image 
// (Using built-in GD font 5 to ensure it works without external .ttf files)
$x = 40;
$y = 15;
imagestring($image, 5, $x, $y, $captcha_text, $text_color);

// Set the header to indicate this is an image
header('Content-Type: image/png');

// Output the image and clear memory
imagepng($image);
imagedestroy($image);
?>