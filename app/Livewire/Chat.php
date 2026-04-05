<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use App\Models\UserNickname;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Chat extends Component
{

    public ?int $activeUserId = null;
    public string $newMessage = '';
    public string $nicknameInput = '';
    public bool $showNicknameModal = false;
    public bool $showChatInfo = false;
    public ?User $activeUser = null;
    public string $search = '';

    public function selectUser(int $userId): void
    {
        $this->activeUserId = $userId;
        $this->newMessage = '';
    }

    public function sendMessage(): void
    {
        if (! $this->activeUserId) return;

        $this->validate(['newMessage' => 'required|string|max:1000']);

        Message::create([
            'user_id'    => Auth::id(),
            'receiver_id' => $this->activeUserId,
            'body'       => $this->newMessage,
        ]);

        $this->newMessage = '';
    }

    public function openNicknameModal(): void
    {
        if (! $this->activeUserId) return;
        $this->nicknameInput = $this->activeUser->userNickname->nickname ?? '';
        $this->showNicknameModal = true;
    }

    public function saveNickname(): void
    {
        $this->validate(['nicknameInput' => 'required|string|max:50']);

        UserNickname::updateOrCreate(
            ['setter_id' => Auth::id(), 'target_id' => $this->activeUserId],
            ['nickname'  => $this->nicknameInput]
        );

        $this->showNicknameModal = false;
        $this->nicknameInput = '';
    }

    public function cancelNickname(): void
    {
        $this->showNicknameModal = false;
        $this->nicknameInput = '';
    }

    public function toggleChatInfo(): void
    {
        $this->showChatInfo = ! $this->showChatInfo;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return $this->redirect('login', navigate: true);
    }

    public function render()
    {
        $messages = [];

        if ($this->activeUserId) {
            $this->activeUser = User::with('userNickname', 'myNicknameFromUser')->find($this->activeUserId);
            $me = Auth::id();

            $messages = Message::with('user')
                ->where(fn($q) => $q->where('user_id', $me)->where('receiver_id', $this->activeUserId))
                ->orWhere(fn($q) => $q->where('user_id', $this->activeUserId)->where('receiver_id', $me))
                ->oldest()
                ->get();
        }

        return view('livewire.chat', [
            'users' => User::with('userNickname', 'myNicknameFromUser')
                ->where('id', '!=', Auth::id())
                ->when($this->search, fn($q) => $q->whereAny(['name', 'email'], 'like', "%{$this->search}%")->orWhereRelation('userNickname', 'nickname', 'like', "%{$this->search}%"))
                ->get(),
            'messages'   => $messages,
        ]);
    }
}
