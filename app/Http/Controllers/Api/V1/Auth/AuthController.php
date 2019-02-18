<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Services\AuthService;
use App\Services\AuthRolesService;
use App\Services\AuthGroupsService;
use App\Services\AuthUsersGroupsService;
use App\Services\AuthGroupsHasRolesService;
use App\Services\KhoaService;

class AuthController extends APIController
{
    public function __construct(AuthService $service,AuthGroupsService $authGroupsService,KhoaService $khoaService,AuthUsersGroupsService $authUsersGroupsService,AuthRolesService $authRolesService,AuthGroupsHasRolesService $authGroupsHasRolesService)
    {
        $this->authService = $service;
        $this->authGroupsService = $authGroupsService;
        $this->khoaService = $khoaService;
        $this->authUsersGroupsService = $authUsersGroupsService;
        $this->authRolesService = $authRolesService;
        $this->authGroupsHasRolesService = $authGroupsHasRolesService;
    }
    public function register(RegisterFormRequest $request)
    {
        
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 201);

   
        
        
    }
    public function login(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        $data = $this->authService->getUserRolesByEmail($request->email);
        $userName = $this->authService->getUserNameByEmail($request->email);
        $extraPayload = array(
            'roles' => $data['roles'],
            'groupId'  => $data['idGroup'][0],
            'userName' => $userName->fullname,
            'userId'   => $userName->id,
        );
        
        return response([
            'status' => 'success',
            'token' => $token,
            'payload' => $extraPayload
        ]);
    }
    

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response([
            'status' => 'success',
            'msg' => 'You have successfully logged out.'
        ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ]);
        }
    }
    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }
    
    public function getListAuthGroups(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $benhVienId = $request->benhVienId;
        $data = $this->authGroupsService->getListAuthGroups($limit, $page, $keyWords,$benhVienId);
        return $this->respond($data);
    }
    
    public function getAuthGroupsByListId(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $id = $request->query('id');
        $data = $this->authGroupsService->getByListId($limit,$page,$id);
        return $this->respond($data);
    }
    
    public function createAuthGroups(Request $request)
    {
        $input = $request->all();
        
        $id = $this->authGroupsService->createAuthGroups($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function getAuthGroupsById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authGroupsService->getAuthGroupsById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function updateAuthGroups($id,Request $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->authGroupsService->updateAuthGroups($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function getTreeListKhoaPhong(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);        
        $benhVienId = $request->query('benhVienId', '');
        $data = $this->khoaService->getTreeListKhoaPhong($limit, $page, $benhVienId);
        return $this->respond($data);
    }
    
    public function getAuthGroupsByUsersId($id,$benhVienId)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authUsersGroupsService->getAuthGroupsByUsersId($id,$benhVienId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListRoles(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);        
        $data = $this->authRolesService->getListRoles($limit, $page);
        return $this->respond($data);
    }
    
    public function getRolesByGroupsId($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authGroupsHasRolesService->getRolesByGroupsId($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getKhoaPhongByGroupsId($id,$benhVienId)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authGroupsService->getKhoaPhongByGroupsId($id,$benhVienId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getKhoaPhongByUserId($id,$benhVienId)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authService->getKhoaPhongId($id,$benhVienId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    
}