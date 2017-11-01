<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientTask;
use App\Project;
use App\Folder;
use App\Googl;
use Debugbar;
use Session;
use Auth;
use App\Mail\Confirm;
use Illuminate\Support\Facades\Mail;
use PropertyStream\Facades\TW;


class DriveController extends Controller
{
    public $client, $token, $service, $folder_id, $folder;
    // // returns the login page with the Google Auth
    public function index(Googl $googl, Request $request)
    {
    // updates all projects
        TW::updateAll();
        if (!session('user.token')) {
            $client = $googl->client();
            if ($request->has('code') || session('user.token')) {
                if ($request->has('code') && !session('user.token')) {
                    $client->authenticate($request->input('code'));
		    // dd($request->input('code'));
                    $this->token = $client->getAccessToken();
                    session(['user' => ['token' => $this->token]]);
                    $client->setAccessToken(session('user.token'));
                } else {
                    $client->setAccessToken(session('user.token'));
                }
		// redirects to the admin control panel or to the user homepage
                if (null === Auth::user()->isAdmin()) {
                    return redirect('/home');
                } else {
                    return redirect('/admin/cpanel');
                }
            } else {
                $auth_url = $client->createAuthUrl();
                return redirect($auth_url);
            }
        } else {
            if (Auth::user()) {
                if (Auth::user()->isAdmin()) {
                    return redirect('/admin/cpanel');
                } else {
                    $userId = Auth::user()->id;
                    $projects = Project::where('fk_user', $userId)->get();
                    $folders = Folder::where('fk_user', $userId)->get();

		    //returns the user's hompage view with all his projects
                    return view('home', compact('projects', 'folders'));
                }
            } else {
		// something went wrong
                return redirect('/');
            }
        }
    }

    //redirects to the login page
    public function login()
    {
        if (Auth::user()) {
            return redirect('/home');
        } else {
            return view('home');
        }
    }

    public function getToken(Googl $googl, Request $request)
    {
        var_dump(session('user'));

    }

    public static function drive(Googl $googl, Request $request, $project_id, $user_id, $folder_id)
    {
        $client = $googl->client();
        if (!session('user.token')) {
            return redirect('/home');
        }
        $client->setAccessToken(session('user.token'));

        $project['id'] = $project_id;
        $project['name'] = Project::where('project_id', $project_id)->first()->project_name;
        session(['folder_id' => $folder_id]);
        session(['user_id' => $user_id]);

        $data['folder_id'] = session('folder_id');
        $data['user_id'] = session('user_id');

        $service = $googl->drive($client);
        $folders = Folder::where('fk_user', Auth::user()->id)->get();
        $optParams = array(
            'spaces' => 'drive',
            'pageSize' => 1000,
            'q' => "'" . $folder_id . "' in parents and trashed=false ",
            'fields' => 'nextPageToken, files(id, name, mimeType,iconLink,thumbnailLink)'
        );
        $files = $service->files->listFiles($optParams);
        if (count($files->getFiles()) == 0) {
            print "No files found.<br>";
        } else {
            $i = 0;
            foreach ($files->getFiles() as $file) {
                $mime = explode('/', $file->getMimeType());
                $ext2 = explode('.', $mime[1]);
                if (count($ext2) >= 1) {
                    $mime[1] = $ext2[count($ext2) - 1];
                }
                $results[$i]['icon'] = $file->iconLink;
                $results[$i]['mime'] = $mime[1];
                $results[$i]['src'] = $file->getThumbnailLink();
                $results[$i]['name'] = $file->getName();
                $i++;
            }
            $results = collect($results);
            $client_tasks = ClientTask::where('fk_project',$request->project_id)->where('fk_user',$request->user_id)->where('fk_tag',1)->where('visible',1)->where('active',1)->where('completed',0)->get();

            return view('user.folder', compact('folders', 'project', 'results', 'data','client_tasks'));
        }

    }

    public function doUpload(Googl $googl, Request $request)
    {

        $client = $googl->client();
        $client->setAccessToken(session('user.token'));

        $drive = $googl->drive($client);
        $file = $googl->file();

        if (null == ($request->fileToUpload)) {
            return redirect('/project/'.$request->project_id.'/user/' . $request->user_id . '/folder/' . $request->folder_id)->with('error', 'Please, choose a file');
        }
        if (null == ($request->name)) {
            return redirect('/project/'.$request->project_id.'/user/' . $request->user_id . '/folder/' . $request->folder_id)->with('error', 'Please, insert a name');
        }
        $name = $request->name;

        $data = file_get_contents($request->fileToUpload);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $request->fileToUpload);

        $createdFile = $drive->files->create($file, array(
            'data' => $data,
            'mimeType' => $mime,
            'uploadType' => 'multipart',
            'postBody' => [
                'name' => $name,
                'parents' => [
                    $request->folder_id,
                ],
            ],
        ));


        return redirect('/project/'.$request->project_id.'/user/' . $request->user_id . '/folder/' . $request->folder_id)->with('upload', 'Upload complete');
    }

}
