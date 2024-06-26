<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Models\ShareLink;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Aws\S3\S3Client;
use Str;
use Illuminate\Http\Request;

class ShareLinkController extends Controller
{
    public function handleShareLink(Request $request, $token)
    {
        // dd($token);
        $shareLink = ShareLink::where('token', $token)->first();

        if (!$shareLink || $shareLink->expiration_date < now()) {
            return response()->json(['error' => 'Share link has expired or is invalid'], 404);
        }

        $file = File::find($shareLink->file_id);
        
       
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
        ]);
    
        $bucket = env('AWS_BUCKET');
        $key = 'uploads/' . $file->filename;
    
        try {
            $object = $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);
    
            $fileContents = $object['Body']->getContents();
            $decryptedFileContents = Crypt::decryptString($fileContents);

            $checkUser=User::findOrFail($file->user_id);
            $checkUser->total_downloads=$checkUser->total_downloads + 1;
            $checkUser->save();
            
            return response()->make($decryptedFileContents, 200)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $file->filename . '"');
                
        } catch (Aws\S3\Exception\S3Exception $e) {
            $logger->error('Error downloading file from S3: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'File not found or unable to download'], 404);
        }

    }
}