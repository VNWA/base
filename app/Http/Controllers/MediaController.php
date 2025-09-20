<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class MediaController extends Controller
{
    public function index()
    {
        return Inertia::render('media');
    }
    private function getFileType($extension)
    {
        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif', 'bmp'],
            'video' => ['mp4', 'avi', 'mov', 'mkv'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
        ];

        foreach ($types as $type => $extensions) {
            if (in_array(strtolower($extension), $extensions)) {
                return $type;
            }
        }

        return 'other';
    }
    private function readDirectory($path)
    {
        $items = [];

        // Lấy thư mục con
        foreach (Storage::directories($path) as $directory) {
            if ($this->isIgnored($directory))
                continue;

            $items[] = [
                'name' => basename($directory),
                'type' => 'dir',
                'path' => $directory,
                'avatar' => $this->imageUrl('vnwa_setup/folder.png')
            ];
        }

        // Lấy file
        foreach (Storage::files($path) as $file) {
            if ($this->isIgnored($file))
                continue;

            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $fileType = $this->getFileType($extension);
            switch ($fileType) {
                case 'image':
                    $avatar = $file;
                    break;
                case 'video':
                    $avatar = 'vnwa_setup/video.jpg';
                    break;
                case 'document':
                    $avatar = 'vnwa_setup/document.png';
                    break;
                default:
                    $avatar = 'vnwa_setup/file.png';
                    break;
            }
            $items[] = [
                'name' => basename($file),
                'type' => 'file',
                'file_type' => $fileType,
                'path' => $file,
                'avatar' => $this->imageUrl($avatar),
                'size' => Storage::size($file),
                'mimeType' => Storage::mimeType($file),
                'lastModified' => date('Y-m-d H:i:s', Storage::lastModified($file)),
            ];
        }

        return $items;
    }

    private function isIgnored($path)
    {
        return in_array(basename($path), ['.gitignore', '.DS_Store', 'Thumbs.db']);
    }
    public function loadData(Request $request)
    {
        $rq = $request->validate([
            'path' => 'nullable|string'
        ]);

        $basePath = $rq['path'] ?? '/';
        if ($basePath === '/' || $basePath === '') {
            $basePath = '';
        }
        $items = $this->readDirectory($basePath);

        return response()->json($items, 200);
    }

    public function createDirectory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'basePath' => 'nullable|string',
        ]);

        // Chuẩn hóa tên folder
        $directoryName = preg_replace('/\s+/', '-', trim($request->input('name')));
        $basePath = $request->input('basePath', '');
        $fullPath = rtrim($basePath, '/') . '/' . $directoryName;

        if (Storage::exists($fullPath)) {
            return response()->json(['message' => 'Folder already exists.'], 409);
        }

        try {
            Storage::makeDirectory($fullPath);
            return response()->json(['message' => 'Create Folder Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function upload(Request $request)
    {
        $MAX_FILES = 10; // tối đa số file
        $MAX_SIZE_MB = 5; // MB
        $MAX_SIZE = $MAX_SIZE_MB * 1024 * 1024;

        $request->validate([
            'dir_path' => 'nullable|string',
            'files' => 'required|array',
            'files.*' => 'file|max:' . ($MAX_SIZE / 1024), // Laravel tính theo KB
        ]);

        $dirPath = $request->input('dir_path', '');
        $files = $request->file('files', []);

        if (count($files) > $MAX_FILES) {
            return response()->json([
                'message' => "Bạn chỉ được upload tối đa {$MAX_FILES} file"
            ], 422);
        }

        $uploadedFiles = [];

        foreach ($files as $file) {
            $path = $file->store($dirPath, 'public');
            // Lưu trong storage/app/public/{dirPath}

            $uploadedFiles[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mimeType' => $file->getMimeType(),
                'url' => Storage::url($path),
            ];
        }

        return response()->json([
            'message' => 'Upload thành công',
            'files' => $uploadedFiles
        ], 200);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            '*.value' => 'required|string',
            '*.label' => 'required|string',
        ]);

        foreach ($data as $item) {
            $path = $item['value'];
            if (Storage::exists($path)) {
                if (Storage::directoryExists($path)) {
                    Storage::deleteDirectory($path);
                } else {
                    Storage::delete($path);
                }
            }
        }

        return response()->json([
            'message' => 'Xoá thành công',
            'removed' => $data,
        ]);
    }

    public function rename(Request $request)
    {
        $request->validate([
            'currentName' => 'required|string',
            'newName' => 'required|string',
            'type' => 'required|string',
            'path' => 'nullable|string',
        ]);

        // Chuẩn hóa tên mới
        $newName = preg_replace('/\s+/', '-', trim($request->newName));
        $currentName = $request->currentName;
        $path = ($request->path ? '/' . trim($request->path, '/') : '');

        if ($request->type === 'file') {
            $fileInfo = pathinfo($currentName);
            $fileExtension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
            $newFileName = $newName . $fileExtension;

            $oldPath = $path . '/' . $currentName;
            $newPath = $path . '/' . $newFileName;

            if (!Storage::disk('public')->exists($oldPath)) {
                return response()->json(['message' => 'File không tồn tại.'], 404);
            }

            if (Storage::disk('public')->exists($newPath)) {
                return response()->json(['message' => 'Tên file đã tồn tại.'], 409);
            }

            Storage::disk('public')->move($oldPath, $newPath);
        } else {
            $oldPath = $path . '/' . $currentName;
            $newPath = $path . '/' . $newName;

            if (!Storage::disk('public')->exists($oldPath)) {
                return response()->json(['message' => 'Thư mục không tồn tại.'], 404);
            }

            if (Storage::disk('public')->exists($newPath)) {
                return response()->json(['message' => 'Tên thư mục đã tồn tại.'], 409);
            }

            Storage::disk('public')->move($oldPath, $newPath);
        }

        return response()->json(['message' => 'Đổi tên thành công.'], 200);
    }



}