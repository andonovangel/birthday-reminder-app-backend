<?php

namespace App\Http\Controllers;

use App\DTO\BirthdayDTO;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use App\Services\{BirthdayService, GroupService};
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
        $this->birthdayService->createBirthday(
            BirthdayDTO::fromRequest($request)
        );
        
        return redirect('/');
    }

    public function showEditBirthday(Birthday $birthday)
    {
        if (auth()->user()->id !== $birthday->user_id) {
            return redirect('/');
        }

        $groups = [];
        if (auth()->check()){
            $groups = $this->groupService->findAll();
        }
        
        return view('birthday/edit-birthday', ['birthday' => $birthday, 'groups' => $groups]);
    }

    public function editBirthday(Birthday $birthday, BirthdayStoreRequest $request)
    {
        if (auth()->user()->id !== $birthday->user_id) {
            return redirect('/');
        }

        $this->birthdayService->updateBirthday(
            $birthday, BirthdayDTO::fromRequest($request)
        );

        return redirect('/');
    }

    public function archivedBirthdays() {
        $birthdays = [];
        $groups = [];
        if (auth()->check()){
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
