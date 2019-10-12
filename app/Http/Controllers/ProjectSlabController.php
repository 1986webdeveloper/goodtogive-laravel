<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddProjectSlabAdminRequest;

class ProjectSlabController extends Controller
{
    /**
     * Determine Project Slab.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of project slab
     */
    public static function projectSlabManagement(Request $request){
        $title = "Project Donation Slab List";
        return view('projectslab/projectslab', compact('title'));
    }

    /**
     * Determine project slab list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getProjectSlab(Request $request){
        $projectDonationSlabDetailObj  = new \App\ProjectDonationSlab;
        $page = datatables()->of($projectDonationSlabDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('project_name', function ($page) {
                    $projectDetailObj  = new \App\Project;
                    $project = $projectDetailObj->project_detail($page->project_id);
                    return $project->name;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_project_slab') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_project_slab/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }
    
    /**
     * Determine fund name screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function addProjectSlab(Request $request){
        $title = "Add Project Donation Slab";
        $projectDetailObj = new \App\Project;
        $projects = $projectDetailObj->all()->where('is_deleted','0');
        return view('projectslab/addprojectslab', compact('title', 'projects'));
    }

    /**
     * Determine method to add project slab.
     *
     * @param  \App\Http\Requests\AddProjectSlabAdminRequest  $request
     * @return response
     */
    public static function actionAddProjectSlab(AddProjectSlabAdminRequest $request){
        $input = $request->all();
        $projectSlabDetailObj = new \App\ProjectDonationSlab;
        $projectSlabDetailObj->create($input);
        $request->session()->flash('message', 'Project donation slab has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('project-slab-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update project slab screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editProjectSlab(Request $request,$id){
        $title = "Edit Project Donation Slab";
        $projectSlabDetailObj = new \App\ProjectDonationSlab;
        $projectSlab = $projectSlabDetailObj->find($id);
        $projectDetailObj = new \App\Project;
        $projects = $projectDetailObj->all()->where('is_deleted','0');
        return view('projectslab/editprojectslab', compact('title','projectSlab','projects'));
    }

    /**
     * Determine method to update project slab.
     *
     * @param  \App\Http\Requests\AddProjectSlabAdminRequest  $request
     * @return response
     */
    public static function actionEditProjectSlab(AddProjectSlabAdminRequest $request){
        $projectSlabDetailObj = new \App\ProjectDonationSlab;
        $input = $request->all();
        $projectSlab = $projectSlabDetailObj->find($input['id']);
        $request->session()->flash('message', 'Project Slab has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('project-slab-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete project slab.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteProjectSlab(Request $request, $id){
        $projectSlabDetailObj = new \App\ProjectDonationSlab;
        $projectSlab = $projectSlabDetailObj->find($id);
        $projectSlab->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Project slab has been deleted successfully.');
        return redirect('project_slab');
    }
}
