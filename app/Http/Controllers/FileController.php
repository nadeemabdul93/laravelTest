<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\ShareLink;
use App\Models\User;
use Illuminate\Http\Request;
// use CloudinaryLaravel\Facades\Cloudinary;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Aws\S3\S3Client;
use Str;
use Illuminate\Support\Facades\File as storedFile;
class FileController extends Controller

{
    public function index(){
        // dd(auth()->user()->files);
        return view('files');
    }
    public function upload(Request $request)
    {
        // ini_set('upload_max_filesize', '50M');
        // ini_set('post_max_size', '50M');
        // ini_set('max_execution_time', '300');
        $request->validate([
            'file' => 'required|file',
        ]);
        

        // Retrieve file details
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file_type = $file->getClientOriginalExtension();
        $file_size = $file->getSize();
       
        // Temporary storage path
        $tmpPath = $file->getRealPath(); // Use the temporary path directly

        // Encrypt the file contents
        $encrypted_file = Crypt::encryptString(file_get_contents($tmpPath));
        // echo $encrypted_file;
        // Initialize S3 client
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Upload file to S3
        $result = $s3Client->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'uploads/' . $filename,
            'Body' => $encrypted_file,
        ]);

        $file_model = new File();
        $file_model->filename = $filename;
        $file_model->file_type = $file_type;
        $file_model->file_size = $file_size;
        $file_model->user_id = auth()->user()->id;
        $query=$file_model->save();
        
        if($query== true){
            $checkUser=User::findOrFail(auth()->user()->id);
            $checkUser->total_uploads=$checkUser->total_uploads + 1;
            $checkUser->save();
            return redirect()->route('files');

        }else{
            return back()->withErrors(['message' => 'Something went wrong.']);
            // return back()->with(['error' => 'Failed try again']);
        }
        
    }

    public function download(Request $request, $id)
    {
        $file = File::find($id);

       
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
    
            // return response()->make($decryptedFileContents, 200)
            //     ->header('Content-Type', 'application/octet-stream')
            //     ->header('Content-Disposition', 'attachment; filename="' . $file->filename . '"');
                return response()->streamDownload(function () use ($decryptedFileContents) {
                    echo $decryptedFileContents;
                }, $file->filename, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $file->filename . '"',
                ])->send();
                return redirect()->back()->with('success', 'File downloaded successfully.');
    
        } catch (Aws\S3\Exception\S3Exception $e) {
            $logger->error('Error downloading file from S3: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'File not found or unable to download'], 404);
        }

        
    }

    public function generateShareLink(Request $request, File $file)
    {

        $shareLink = new ShareLink();
        $shareLink->file_id = $file->id;
        $shareLink->token = Str::random(32);
        $shareLink->share_link = $shareLink->token;
        $shareLink->expiration_date = now()->addDays(30);
        $shareLink->save();

        return response()->json(['share_link' => route('share', $shareLink->token)]);
    }
    public function generateLink(Request $request)
    {
        
        // Validate the incoming request, assuming you're receiving a file ID
        $request->validate([
            'file_id' => 'required|exists:files,id',
        ]);

        $file = File::findOrFail($request->file_id);

        $shareLink = new ShareLink();
        $shareLink->file_id = $file->id;
        $shareLink->token = Str::random(32);
        $shareLink->share_link = $shareLink->token;
        $shareLink->expiration_date = now()->addDays(30);
        $shareLink->save();
        $link = url('share/' . $shareLink->token);

        // Return a JSON response with the generated link
        return response()->json(['link' => $link]);
    }
}