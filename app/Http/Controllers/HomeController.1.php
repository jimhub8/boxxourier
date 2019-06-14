<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileManagement\Props;
use App\FileManagement\Repositories\Attachment\AttachmentRepository;
use Auth;
use Exception;
use App\AttachmentCategory;
use App\Attachment;
use App\User;
use App\Company;
use Spatie\Permission\Models\Permission;
use App\Country;
class HomeController extends Controller
{
    public function courier()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }
        $user = Auth::user();
        $country = Country::find($user->country_id);
        // dd($country);
        $user->country_name = $country->country_name;
        // $users->transform(function ($user, $key) {
        //     $country = Country::find($user->country_id);
        //     $user->country_name = $country->name;
		// 	return $user;
        // });
        // dd(json_decode(json_encode((Auth::user()), false)));
        $auth_user = array_prepend($user->toArray(), $permissions, 'can');
        return view('welcome', compact('auth_user'));
    }
    public function courierHome()
    {
        return redirect('/')->where('name', '[A-Za-z]+');
    }
    public function docs()
    {
        $category = AttachmentCategory::all();
        $docs = Attachment::paginate(10);
        return view('docs', ['category' => $category, 'docs' => $docs]);
    }
    /**
     * AttachmentRepository instance
     *
     * @var App\FileManagement\Repositories\AttachmentRepository
     **/
    protected $attachmentRepo;

    public function __construct(AttachmentRepository $attachmentRepo)
    {
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Load homepage
     * 
     * @param  Request $request 
     * @return view           
     */
    public function index(Request $request)
    {
        $props = Props::get();
        // return redirect('/login');
        if (Auth::check()) {
            return redirect('/courier');
        }
    }

    /**
     * Delete an attachment
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function deleteAttachment(Request $request)
    {
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $attachment_id = $request->input('attachment_id');

            if (!$attachment_id) {
                throw new Exception("You are trying to delete a non-existing file", 1);
            }

            $attachment = $this->attachmentRepo->delete(intval($attachment_id));

            return response()->json(array(
                'success' => true,
                'data' => $attachment,
                'errors' => []
            ), 200);

        } catch (\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }

    /**
     * Pull all the attachments for the logged in user
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function pullAttachments(Request $request)
    {
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $user_id = Auth::user()->id;

            $attachments = $this->attachmentRepo->where('user_id', $user_id)->with('category')->orderBy('created_at', 'DESC')->all();

            return response()->json(array(
                'success' => true,
                'data' => $attachments,
                'errors' => []
            ), 200);

        } catch (\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }

    /**
     * Store files locally
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function store(Request $request)
    {
        try {

            $attachments = $this->processAttachments($request);

            if (count($attachments) > 0) {
                $this->attachmentRepo->saveInBulk($attachments);
            }

            return response()->json(array(
                'success' => true,
                'data' => [],
                'errors' => []
            ), 200);

        } catch (\Exception $e) {
            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e
            ), 200);
        }
    }

    /**
     * We are categorizing uploaded files. Since we can't attach data to immutable javascript file objects, we have to
     * send a nested array where the second array (with key = 1) will hold custom data, such as category ID of that file
     * 
     * @param  Request $request 
     * @return array          
     */
    public function processAttachments($request)
    {
        $attachments_input = $request->input('attachments');
        $attachments_files = $request->file('attachments');
        $attachments = [];

        if (count($attachments_files)) {
            foreach ($attachments_files as $key => $value) {
                $category_id = $attachments_input[$key][1] != 'undefined' ? $attachments_input[$key][1] : null;
                $value[0]->category_id = $category_id;
                array_push($attachments, $value[0]);
            }
        }

        return $attachments;
    }

    /**
     * Retrieve a full list of categories
     * 
     * @param  Request $request 
     * @return Response           
     */
    public function getCategories(Request $request)
    {
        try {

            if (!Auth::check()) {
                throw new Exception("User has to be logged in", 1);
            }

            $user_id = Auth::user()->id;

            $categories = $this->attachmentRepo->categories($user_id);

            return response()->json(array(
                'success' => true,
                'data' => $categories,
                'errors' => []
            ), 200);

        } catch (\Exception $e) {

            return response()->json(array(
                'success' => false,
                'data' => 'Server error happened',
                'errors' => $e->getMessage()
            ), 200);

        }
    }

    public function storeCategories(Request $request)
    {
        // return $request->all;
        $category = new AttachmentCategory;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->shareholders = $request->shareholders;
        $category->directors = $request->directors;
        $category->managers = $request->managers;
        $category->employees = $request->employees;
        $category->save();
        return $category;
    }
    public function getCategory()
    {
        return AttachmentCategory::all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttachmentCategory $attachmentCategory)
    {
        // return AttachmentCategory::where('id', $attachmentCategory->id)->delete();
        // return Nse::where('id', $nse->id)->delete();
    }

    public function upload(Request $request)
    {
        return Props::get();

    }

    public function categories()
    {
        return $category = AttachmentCategory::all();
    }

    public function getDocs()
    {
        return Attachment::all();
    }

    public function getClientsDocs()
    {
        $users = User::with('roles')->get();
        $userArr = [];
        foreach ($users as $user) {
            foreach ($user->roles as $role) {
                if ($role->name == 'Customer') {
                    $userArr[] = $role->pivot->user_id;
                }
            }
        }
        $customers = User::with('documents')->whereIn('id', $userArr)->get();
        return $customers;
    }

    public function assign(Request $request, $id)
    {
        // return $request->all();
        $attachment = Attachment::find($id);
        $attachment->client_id = $id;
        $attachment->save();
        return $attachment;
    }

    public function getDocsSort(Request $request)
    {

        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                return Attachment::all();
            } else {
                return Attachment::where('client_id', $request->select['id'])->get();
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->select['id'] == 'all') {
                    return Attachment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->get();
                } else {

                    return Attachment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->get();
                }
            } else {
                return Attachment::where('client_id', $request->select['id'])
                    ->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
                    ->get();
            }
        }
        return Attachment::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
    }
}
