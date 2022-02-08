<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Api\V1\Admin\CreateUserRequest;
use App\Http\Requests\Api\V1\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class UserController extends BaseController
{
    /**
     * paginate
     */
    public const PER_PAGE = 5;

    /**
     * @param Request $request
     * @return UserResourceCollection
     */
    public function index(Request $request): UserResourceCollection
    {
        $query = User::orderByDesc('created_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }
        if (!empty($value = $request->get('first_name'))) {
            $query->where('first_name', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('phone'))) {
            $query->where('phone', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }
        if (!empty($value = $request->get('roles'))) {
            $query->whereHas(
                'roles', static function ($q) use ($value) {
                $q->whereIn('role_id', $value);
            });
        }
        $result = $query->paginate(self::PER_PAGE);
        return new UserResourceCollection($result);
    }

    /**
     * @param CreateUserRequest $request
     * @return UserResourceCollection
     */
    public function store(CreateUserRequest $request): UserResourceCollection
    {
        $user = [
            'phone'             => $request->input('phone'),
            'password'          => Hash::make($request->input('password')),
            'status'            => User::STATUS_ACTIVE,
            'phone_verified_at' => Carbon::now(),
        ];
        $user = User::new($user)
            ->attachRole();

        return (new UserResourceCollection(User::latest()->paginate(self::PER_PAGE)))
            ->additional(
                [
                    'message' => trans('The user has been successfully created',
                        ['phone' => phone_to_string($user->phone)]
                    ),
                ]
            );
    }

    /**
     * @param int $id
     * @return UserResource|JsonResponse
     */
    public function show(int $id)
    {
        try {
            return new UserResource(User::findOrFail($id));
        } catch (ModelNotFoundException $error) {
            return response()->json($error->getMessage(), 404);
        }
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->only(['first_name', 'last_name', 'phone', 'email']);

            $user->update($data);
            /** roles request ids */
            $user->roles()->sync($request->input('roles'));
            return response()->json(
                ['message' => trans('The user has been successfully updated.',
                    ['phone' => phone_to_string($user->phone)]
                )], 200);
        } catch (\DomainException | ModelNotFoundException $error) {
            return response()->json($error->getMessage(), 404);
        }
    }

    /**
     * @param $id
     * @return UserResourceCollection|JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if (auth()->user()->id === $user->id) {
                throw new \RuntimeException('');
            }
            $user->delete();
            return (new UserResourceCollection(User::latest()->paginate(self::PER_PAGE)))
                ->additional(
                    [
                        'message' => trans('The user has been successfully deleted',
                            ['phone' => phone_to_string($user->phone)]
                        ),
                    ]
                );
        } catch (\RuntimeException $error) {
            return response()->json(['error' => trans('User not found')], 404);
        }
    }
}
