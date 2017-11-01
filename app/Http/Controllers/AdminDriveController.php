<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Googl;
use App\Folder;
use Auth;
use App\User;
use Debugbar;

class AdminDriveController extends Controller
{
    public $client, $token, $service, $folder_id, $folder;

    public function getToken($user_id, Googl $googl, Request $request)
    {

        $this->client = $googl->client();
//        $file = $googl->file();
        if ($request->has('code') || session('user.token')) {
            if ($request->has('code') && !session('user.token')) {
                $this->client->authenticate($request->input('code'));
                $this->token = $this->client->getAccessToken();
                session(['user' => ['token' => $this->token]]);
//                $client->setAccessToken($token);
                $this->client->setAccessToken(session('user.token'));
            } else {
                $this->client->setAccessToken(session('user.token'));
            }

            $this->folder_id = $request->folder_id;

//            if (null !== session('folder_id')) {
//                $this->folder_id = session('folder_id');
//                $data['user_id'] = session('user_id');
//                $data['folder_id'] = session('folder_id');
////            } else {
            $data = [];
            $data['folder_id'] = $request->folder_id;
            $data['project_id'] = Folder::where('folder_id',$request->folder_id)->where('fk_user',$request->user_id)->first()->fk_project;
            $data['user_id'] = $user_id;
            $data['user_email'] = User::find($user_id)->email;
//            }
            $this->service = $googl->drive($this->client);

            $this->folder = Folder::where('folder_id', $this->folder_id)->first()->id;
            $folders = Folder::where('fk_user', $user_id)->get();
            $optParams = array(
//            'corpus' => 'domain',
//            'corpus' => 'user',
                'spaces' => 'drive',
                'pageSize' => 1000,
                'q' => "'" . $this->folder_id . "' in parents and trashed=false ",
                'fields' => 'nextPageToken, files(id, name, mimeType,iconLink,thumbnailLink)'
            );
            $results = $this->service->files->listFiles($optParams);
            if (count($results->getFiles()) == 0) {
                print "No files found.<br>";
            } else {
                $i = 0;
                foreach ($results->getFiles() as $file) {
                    $mime = explode('/', $file->getMimeType());
                    $ext2 = explode('.', $mime[1]);
                    $num = count($ext2);
                    if (count($ext2) >= 1) {
                        $mime[1] = $ext2[count($ext2) - 1];
                    }
                    $results[$i]['mime'] = $mime[1];
                    $results[$i]['src'] = $file->getThumbnailLink();
                    $results[$i]['name'] = $file->getName();
                    $i++;
                }
//                return view('user.dashboard');
                return view('admin.folder', compact('folders', 'results', 'data'));
            }

        } else {
            $auth_url = $this->client->createAuthUrl();
            return redirect($auth_url);
        }
    }

//    public function drive()
//    {
//
//    }
//
//    public function dashboard()
//    {
//
//        $this->folder = Folder::where('fk_user', Auth::get()->id)->get();
//        return view('user.dashboard');
//    }
}
