<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\GetUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TinifyService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    public function create(CreateUserRequest $request, TinifyService $tinifyService)
    {
        if (User::query()->where('phone', $request->phone)->orWhere('email', $request->email)->exists()) {
            throw new HttpException(Response::HTTP_CONFLICT, 'User with this phone or email already exist');
        }
        $photo = $request->file('photo');

        $fileName = $this->resizeImageAndSave($photo);

        $tinifyService->optimaze(storage_path('app/public/' . $fileName));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position_id' => $request->position_id,
            'photo' => $fileName
        ]);

        return [
            "success" => true,
            "user_id" => $user->id,
            "message" => "New user successfully registered"
        ];
    }

    public function list(GetUserRequest $request)
    {
        $users = User::query()->paginate($request->get('count', 6));

        $users->withQueryString();

        return [
            "success" => true,
            "page" => $users->currentPage(),
            "total_pages" => $users->lastPage(),
            "total_users" => $users->total(),
            "count" => $users->count(),
            "links" => [
                "next_url" => $users->nextPageUrl(),
                "prev_url" => $users->previousPageUrl()
            ],
            "users" => UserResource::collection($users->items())
        ];

    }

    public function find(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'User not found');
        }

        return [
            'success' => true,
            'user' => new UserResource($user)
        ];
    }

    public function resizeImageAndSave(UploadedFile $photo): string
    {
        $img = Image::read($photo)->resize(70, 70);

        $fileName = $photo->hashName() . '.jpg';

        $path = storage_path('app/public/' . $fileName);

        $img->encodeByExtension($photo->getClientOriginalExtension(), 90)->save($path);

        return $fileName;
    }
}
