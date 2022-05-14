<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $data = [];
        $hasSent = false;
        $members = [];
        foreach($request->emails as $email){
            $user = User::where('email', $email)->first();
            if($user){
                $exist = ProjectMember::where('user_id', $user->id)->where('project_id', $request->project_id)->first();
                $owner = Project::where('user_id', $user->id)->where('id', $request->project_id)->first();
                if(empty($exist)){
                    if(empty($owner)){
                        $member = ProjectMember::create([
                            'project_id' => $request->project_id,
                            'user_id' => $user->id,
                            // 'role_id' => $user->role_id
                        ]);

                        $member->load(['user', 'user.info']);
                        array_push($members, $member);
                        $hasSent = true;
                    }
                }
            }
            else {
                array_push($data, $email);
            }
        }

        if($hasSent){
            return $this->success('Invitations has been sent successfully!', $members);
        }
        else {
            return $this->error('No invitations was sent! Please check if the email addresses are valid.');
        }
      
    }

    public function update(Request $request, $id){
       $projectMember = ProjectMember::where('user_id', $request->user_id)->where('project_id', $request->project_id)->first();
       if($projectMember){
           $projectMember->update([
               'member_role_id' => $request->role_id
           ]);
       }
       else {
           return $this->error('Data passed to the server is invalid');
       }
    }
}
