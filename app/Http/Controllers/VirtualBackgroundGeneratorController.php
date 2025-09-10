<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VirtualBackgroundGeneratorController extends Controller
{
    public function generateBackground($profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Get parameters from request
        $backgroundTemplate = request('background_template', 'professional');
        $showUsername = request('show_username', true);
        $showEmail = request('show_email', true);
        $showAddress = request('show_address', true);
        $showQRCode = request('show_qr_code', true);
        $elementPositions = request('element_positions', []);
        $previewOnly = request('preview_only', false);

        try {
            // Initialize Image Manager
            $manager = new ImageManager(new Driver());

            // Load the background image
            $background = $this->getBackgroundImage($manager, $profile, $backgroundTemplate);

            // Generate QR code with profile URL
            $qrImage = null;
            if ($showQRCode) {
                $profileUrl = config('app.url') . '/profile/' . $profile->id;
                $qrImage = $this->generateQRCode($manager, $profileUrl);
            }
            
            // Create profile photo image if exists
            $profilePhoto = null;
            if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                $profilePhoto = $manager->read(Storage::disk('public')->path($profile->photo));
                $profilePhoto->resize(80, 80);
            }

            // Create the final image with overlays
            $finalImage = $this->createOverlayImage(
                $manager, 
                $background, 
                $qrImage, 
                $profilePhoto, 
                $profile, 
                $showUsername,
                $showEmail,
                $showAddress,
                $showQRCode,
                $elementPositions
            );

            // Save the generated image
            $outputPath = 'uploads/virtual-backgrounds/generated/' . $profile->id . '_' . time() . '.png';
            Storage::disk('public')->put($outputPath, $finalImage->toPng());

            return response()->json([
                'success' => true,
                'download_url' => Storage::url($outputPath),
                'preview_url' => Storage::url($outputPath),
                'background_template' => $backgroundTemplate
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate virtual background: ' . $e->getMessage()], 500);
        }
    }

    private function getBackgroundImage($manager, $profile, $backgroundTemplate)
    {
        // If profile has a custom background, use it
        if ($profile->virtual_background && Storage::disk('public')->exists($profile->virtual_background)) {
            $backgroundPath = Storage::disk('public')->path($profile->virtual_background);
            return $manager->read($backgroundPath);
        }

        // Otherwise, use template background
        return $this->createTemplateBackground($manager, $backgroundTemplate);
    }

    private function createTemplateBackground($manager, $template)
    {
        // Create a 1920x1080 canvas
        $canvas = $manager->create(1920, 1080);

        switch ($template) {
            case 'professional':
                // Professional gradient background
                $this->createGradientBackground($canvas, [41, 128, 185], [44, 62, 80]);
                break;
                
            case 'creative':
                // Creative colorful background
                $this->createGradientBackground($canvas, [155, 89, 182], [142, 68, 173]);
                break;
                
            case 'minimal':
                // Minimal light background
                $this->createGradientBackground($canvas, [236, 240, 241], [189, 195, 199]);
                break;
                
            case 'gradient':
                // Multi-color gradient
                $this->createMultiGradientBackground($canvas);
                break;
                
            case 'abstract':
                // Abstract pattern background
                $this->createAbstractBackground($canvas);
                break;
                
            default:
                // Default professional background
                $this->createGradientBackground($canvas, [52, 152, 219], [41, 128, 185]);
                break;
        }

        return $canvas;
    }

    private function createGradientBackground($canvas, $color1, $color2)
    {
        // Create a simple gradient effect
        for ($y = 0; $y < 1080; $y++) {
            $ratio = $y / 1080;
            $r = $color1[0] + ($color2[0] - $color1[0]) * $ratio;
            $g = $color1[1] + ($color2[1] - $color1[1]) * $ratio;
            $b = $color1[2] + ($color2[2] - $color1[2]) * $ratio;
            
            for ($x = 0; $x < 1920; $x++) {
                $canvas->pixel($x, $y, [$r, $g, $b]);
            }
        }
    }

    private function createMultiGradientBackground($canvas)
    {
        // Create a multi-color gradient
        $colors = [
            [231, 76, 60], // Red
            [155, 89, 182], // Purple
            [52, 152, 219], // Blue
            [46, 204, 113], // Green
            [241, 196, 15]  // Yellow
        ];
        
        for ($y = 0; $y < 1080; $y++) {
            $ratio = $y / 1080;
            $colorIndex = floor($ratio * (count($colors) - 1));
            $localRatio = ($ratio * (count($colors) - 1)) - $colorIndex;
            
            $color1 = $colors[$colorIndex];
            $color2 = $colors[min($colorIndex + 1, count($colors) - 1)];
            
            $r = $color1[0] + ($color2[0] - $color1[0]) * $localRatio;
            $g = $color1[1] + ($color2[1] - $color1[1]) * $localRatio;
            $b = $color1[2] + ($color2[2] - $color1[2]) * $localRatio;
            
            for ($x = 0; $x < 1920; $x++) {
                $canvas->pixel($x, $y, [$r, $g, $b]);
            }
        }
    }

    private function createAbstractBackground($canvas)
    {
        // Create an abstract pattern
        $this->createGradientBackground($canvas, [52, 73, 94], [44, 62, 80]);
        
        // Add some abstract shapes
        for ($i = 0; $i < 10; $i++) {
            $x = rand(0, 1920);
            $y = rand(0, 1080);
            $size = rand(50, 200);
            $color = [rand(100, 255), rand(100, 255), rand(100, 255)];
            
            for ($dx = -$size; $dx < $size; $dx++) {
                for ($dy = -$size; $dy < $size; $dy++) {
                    if ($dx * $dx + $dy * $dy < $size * $size) {
                        $px = $x + $dx;
                        $py = $y + $dy;
                        if ($px >= 0 && $px < 1920 && $py >= 0 && $py < 1080) {
                            $canvas->pixel($px, $py, $color);
                        }
                    }
                }
            }
        }
    }

    private function makeCircular($image)
    {
        // Create a circular mask
        $width = $image->width();
        $height = $image->height();
        $size = min($width, $height);
        
        // Resize to square
        $image->resize($size, $size);
        
        // Create circular mask
        $mask = $this->createCircularMask($image->manager(), $size);
        
        // Apply mask
        $image->mask($mask);
        
        return $image;
    }
    
    private function createCircularMask($manager, $size)
    {
        $mask = $manager->create($size, $size, [0, 0, 0, 0]);
        
        // Create circular shape
        $center = $size / 2;
        $radius = $size / 2;
        
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $distance = sqrt(pow($x - $center, 2) + pow($y - $center, 2));
                if ($distance <= $radius) {
                    $mask->pixel($x, $y, [255, 255, 255, 255]);
                }
            }
        }
        
        return $mask;
    }

    private function generateQRCode($manager, $url)
    {
        try {
            // Try to use SimpleSoftwareIO/simple-qrcode if available
            if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(200)
                    ->margin(0)
                    ->generate($url);
                
                // Convert QR code to Intervention Image
                $qrImage = $manager->read($qrCode);
                return $qrImage;
            }
        } catch (\Exception $e) {
            // Fallback if QR code library fails
        }

        // Fallback: Create a QR code placeholder with URL text
        $qrImage = $manager->create(200, 200, [255, 255, 255]);
        
        // Add border
        $qrImage->rectangle(0, 0, 199, 199, function($draw) {
            $draw->color([0, 0, 0]);
            $draw->width(2);
        });
        
        // Add QR code pattern (simplified)
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                if (($i + $j) % 2 == 0) {
                    $qrImage->rectangle($i * 25, $j * 25, ($i + 1) * 25, ($j + 1) * 25, function($draw) {
                        $draw->color([0, 0, 0]);
                    });
                }
            }
        }
        
        // Add URL text at bottom
        $qrImage->text($url, 100, 180, function($font) {
            $font->size(8);
            $font->color([0, 0, 0]);
            $font->align('center');
        });
        
        return $qrImage;
    }

    private function createOverlayImage($manager, $background, $qrImage, $profilePhoto, $profile, $showUsername, $showEmail, $showAddress, $showQRCode, $elementPositions)
    {
        // Resize background to standard virtual background size (1920x1080)
        $background->resize(1920, 1080);

        // Create a new canvas with the background
        $canvas = $manager->create(1920, 1080);
        $canvas->place($background, 'center');

        // Apply template-specific styling
        $this->applyTemplate($manager, $canvas);

        // Add QR code if enabled
        if ($showQRCode && $qrImage) {
            $qrImage->resize(150, 150);
            $x = isset($elementPositions['qr_code']['x']) ? $elementPositions['qr_code']['x'] : 1700;
            $y = isset($elementPositions['qr_code']['y']) ? $elementPositions['qr_code']['y'] : 50;
            $canvas->place($qrImage, 'top-left', $x, $y);
        }

        // Add profile photo if exists
        if ($profilePhoto) {
            $profilePhoto = $this->makeCircular($profilePhoto);
            $x = isset($elementPositions['profile_photo']['x']) ? $elementPositions['profile_photo']['x'] : 50;
            $y = isset($elementPositions['profile_photo']['y']) ? $elementPositions['profile_photo']['y'] : 200;
            $canvas->place($profilePhoto, 'top-left', $x, $y);
        }

        // Add profile information based on visibility settings
        $this->addProfileInfo($manager, $canvas, $profile, $profilePhoto, $showUsername, $showEmail, $showAddress, $elementPositions);

        return $canvas;
    }

    private function applyTemplate($manager, $canvas)
    {
        // Default template with medium overlay
        $overlay = $manager->create(1920, 1080, [0, 0, 0, 70]); // 27% opacity
        $canvas->place($overlay, 'top-left');
    }

    private function addProfileInfo($manager, $canvas, $profile, $profilePhoto, $showUsername, $showEmail, $showAddress, $elementPositions)
    {
        $textColor = $this->getTemplateTextColor('default'); // Default color for now
        $nameSize = $this->getTemplateNameSize('default'); // Default size for now
        $titleSize = $this->getTemplateTitleSize('default'); // Default size for now
        $addressSize = $this->getTemplateAddressSize('default'); // Default size for now

        // Position profile info based on element positions
        $startX = isset($elementPositions['username']['x']) ? $elementPositions['username']['x'] : 50;
        $startY = isset($elementPositions['username']['y']) ? $elementPositions['username']['y'] : 50;

        // Add name if enabled
        if ($showUsername && ($profile->name || $profile->username)) {
            try {
                $name = $profile->name ?: $profile->username;
                $x = isset($elementPositions['username']['x']) ? $elementPositions['username']['x'] : $startX;
                $y = isset($elementPositions['username']['y']) ? $elementPositions['username']['y'] : $startY;
                
                $canvas->text($name, $x, $y, function($font) use ($nameSize, $textColor) {
                    $font->size($nameSize);
                    $font->color($textColor);
                    $font->weight('bold');
                });
            } catch (\Exception $e) {
                // If text fails, skip it
            }
        }

        // Add email if enabled
        if ($showEmail && $profile->email) {
            try {
                $x = isset($elementPositions['email']['x']) ? $elementPositions['email']['x'] : $startX;
                $y = isset($elementPositions['email']['y']) ? $elementPositions['email']['y'] : ($startY + $nameSize + 10);
                
                $canvas->text($profile->email, $x, $y, function($font) use ($titleSize, $textColor) {
                    $font->size($titleSize);
                    $font->color($textColor);
                });
            } catch (\Exception $e) {
                // If text fails, skip it
            }
        }

        // Add address if enabled
        if ($showAddress && $profile->address) {
            try {
                $x = isset($elementPositions['address']['x']) ? $elementPositions['address']['x'] : $startX;
                $y = isset($elementPositions['address']['y']) ? $elementPositions['address']['y'] : ($startY + $nameSize + $titleSize + 20);
                
                $canvas->text($profile->address, $x, $y, function($font) use ($addressSize, $textColor) {
                    $font->size($addressSize);
                    $font->color($textColor);
                });
            } catch (\Exception $e) {
                // If text fails, skip it
            }
        }
    }

    private function getTemplateTextColor($template)
    {
        switch ($template) {
            case 'professional':
                return [255, 255, 255]; // White
            case 'creative':
                return [255, 255, 255]; // White
            case 'minimal':
                return [255, 255, 255]; // White
            case 'branded':
                return [255, 255, 255]; // White
            default:
                return [255, 255, 255]; // White
        }
    }

    private function getTemplateNameSize($template)
    {
        switch ($template) {
            case 'professional':
                return 28;
            case 'creative':
                return 32;
            case 'minimal':
                return 24;
            case 'branded':
                return 26;
            default:
                return 24;
        }
    }

    private function getTemplateTitleSize($template)
    {
        switch ($template) {
            case 'professional':
                return 20;
            case 'creative':
                return 24;
            case 'minimal':
                return 18;
            case 'branded':
                return 20;
            default:
                return 18;
        }
    }

    private function getTemplateAddressSize($template)
    {
        switch ($template) {
            case 'professional':
                return 16;
            case 'creative':
                return 18;
            case 'minimal':
                return 14;
            case 'branded':
                return 16;
            default:
                return 16;
        }
    }

    public function downloadBackground($profileId)
    {
        $profile = Profile::find($profileId);
        
        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Generate the background
        $result = $this->generateBackground($profileId);
        
        if (isset($result->getData()->error)) {
            return $result;
        }

        $data = $result->getData();
        $filePath = str_replace('/storage/', '', $data->download_url);
        $fullPath = Storage::disk('public')->path($filePath);

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'Generated file not found'], 404);
        }

        return response()->download($fullPath, 'virtual_background_' . $profile->name . '.png');
    }
} 