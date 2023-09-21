<?php

namespace App\Http\Controllers;

use App\DTO\BirthdayDTO;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use App\Services\BirthdayService;
use App\Services\GroupService;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    private BirthdayService $birthdayService;
    private GroupService $groupService;

    public function __construct(BirthdayService $birthdayService, GroupService $groupService)
    {
        $this->birthdayService = $birthdayService;
        $this->groupService = $groupService;
    }

    public function index() {
        $birthdays = [];
        $groups = [];
        if (auth()->check()){
            // $birthdays = $user->usersBirthdayReminders()->latest()->get();
            // $groups = $user->usersGroups()->latest()->get();
            $birthdays = $this->birthdayService->findAll();
            $groups = $this->groupService->findAll();
        }

        return view('birthday/home', [
            'birthdays' => $birthdays, 
            'groups' => $groups,
            'user' => auth()->user()
        ]);
    }

    public function createBirthday(BirthdayStoreRequest $request)
    {
        // $incomingFields = $request->only([
        //     'name', 'title', 'body', 'phone_number', 'birthday_date', 'group_id'
        // ]);

        // $incomingFields['name'] = strip_tags($incomingFields['name']);
        // $incomingFields['title'] = strip_tags($incomingFields['title']);
        // $incomingFields['body'] = strip_tags($incomingFields['body']);
        // $incomingFields['phone_number'] = strip_tags($incomingFields['phone_number']);
        // $incomingFields['birthday_date'] = strip_tags($incomingFields['birthday_date']);
        
        // $incomingFields['group_id'] = is_numeric($incomingFields['group_id']) ? strip_tags($incomingFields['group_id']) : NULL;
        
        // $incomingFields['user_id'] = auth()->id();

        // Birthday::create($incomingFields);

        $this->birthdayService->createBirthday(
            BirthdayDTO::fromApiRequest($request)
        );
        
        return redirect('/');
    }

    public function showEditBirthday(string $id)
    {
        // $user = auth()->user();
        // if ($user->id !== $birthday->user_id) {
        //     return redirect('/');
        // }

        $birthday = [];
        $groups = [];
        if (auth()->check()){
            $birthday = $this->birthdayService->findBirthday($id);
            $groups = $this->groupService->findAll();
        }
        
        return view('birthday/edit-birthday', ['birthday' => $birthday, 'groups' => $groups]);
    }

    public function editBirthday(Birthday $birthday, BirthdayStoreRequest $request)
    {
        if (auth()->user()->id !== $birthday->user_id) {
            return redirect('/');
        }
        $birthday = $this->birthdayService->updateBirthday($request, $birthday);

        // $incomingFields = $request->only([
        //     'name', 'title', 'body', 'phone_number', 'birthday_date', 'group_id'
        // ]);

        // $incomingFields['name'] = strip_tags($incomingFields['name']);
        // $incomingFields['title'] = strip_tags($incomingFields['title']);
        // $incomingFields['body'] = strip_tags($incomingFields['body']);
        // $incomingFields['phone_number'] = strip_tags($incomingFields['phone_number']);
        // $incomingFields['birthday_date'] = strip_tags($incomingFields['birthday_date']);
        
        // $incomingFields['group_id'] = is_numeric($incomingFields['group_id']) ? strip_tags($incomingFields['group_id']) : NULL;

        // $birthday->update($incomingFields);
        return redirect('/');
    }

    public function archivedBirthdays() {
        $user = auth()->user();

        $birthdays = [];
        $groups = [];
        if (auth()->check()){
            // $birthdays = $user->usersBirthdayReminders()
            //             ->latest()->onlyTrashed()->get();
            // $groups = $user->usersGroups()->latest()->get();

            $birthdays = $this->birthdayService->findAllTrashed();
            $groups = $this->groupService->findAllTrashed();
        }

        return view('birthday/archived-birthdays', ['birthdays' => $birthdays, 'groups' => $groups]);
        
    }

    public function deleteBirthday(Birthday $birthday)
    {
        if (auth()->user()->id == $birthday['user_id']) {
            if ($birthday->trashed()) {
                $birthday->forceDelete();

                return redirect('/');
            }
     
            $birthday->delete();
        }
        return redirect('/');
    }

    public function restoreBirthday(Birthday $birthday) {
        $birthday->restore();

        return redirect('/');
    }

    public function listBirthdays(Request $request) {
        $user = auth()->user();

        if ($user) {
            $sort = $request->input('sort', '');
            $search = $request->input('search', '');

            if ($request->input('order') === 'desc') {
                $order = 'desc';
            } else {
                $order = 'asc';
            }

            $birthdays = Birthday::where('user_id', $user->id);

            if ($search !== '') {
                $birthdays->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('title', 'like', "%$search%") 
                            ->orWhere('phone_number', 'like', "%$search%")
                            ->orWhere('body', 'like', "%$search%");
                    });
            }

            if ($sort === 'date') {
                $birthdays->orderBy('birthday_date', $order);
            } else if ($sort === 'alphabetical') {
                $birthdays->orderBy('name', $order);
            }
        }

        $birthdays = $birthdays->get();

        return view('birthday/list-birthdays', compact('birthdays', 'user', 'search', 'sort', 'order'));
    }
}
