<?php

namespace App\Http\Requests;

use App\Http\Enums\GroupUserStatus;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InviteUsersRequest extends FormRequest
{
    public Group $group;
    public ?User $user = null;
    public ?GroupUser $groupUser = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\Group $group */
        $this->group = $this->route('group');

        return $this->group->isAdmin(Auth::id());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', function ($attribute, $value, \Closure $fail) {
                $this->user = User::query()->where('email', $value)
                    ->orWhere('username', $value)
                    ->first();

                if (!$this->user) {
                    $fail('User does not exist');
                }

                $this->groupUser = GroupUser::where('user_id', $this->user->id)
                    ->where('group_id', $this->group->id)
                    ->first();

                if ($this->groupUser && $this->groupUser->status === GroupUserStatus::APPROVED->value) {
                    $fail('User is already joined to the group');
                }
            }]
        ];
    }
}
