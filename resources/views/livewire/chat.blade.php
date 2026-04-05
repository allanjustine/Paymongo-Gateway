<div class="flex h-screen bg-[#0f0c29] text-white overflow-hidden">

    {{-- Sidebar --}}
    <div class="w-72 flex flex-col border-r border-white/5 bg-white/[0.03]">

        {{-- Profile Header --}}
        <div class="p-5 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center font-bold text-sm shadow-lg shadow-violet-500/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/30 truncate">{{ Auth::user()->email }}</p>
                </div>
                <form wire:submit="logout" class="ml-auto shrink-0">
                    <button type="submit" title="Logout"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-white/30 hover:text-red-400 hover:bg-red-500/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Search bar --}}
        <div class="px-4 py-3 border-b border-white/5">
            <div class="flex items-center gap-2 bg-white/5 rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-white/20 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.200ms="search" type="search" placeholder="Search conversations..."
                    class="flex-1 bg-transparent text-xs text-white/60 placeholder-white/20 focus:outline-none" />
            </div>
        </div>

        {{-- Users list --}}
        <div class="flex-1 overflow-y-auto py-2">
            <p class="px-4 py-2 text-[10px] font-semibold text-white/20 uppercase tracking-widest">Messages</p>
            @forelse ($users as $user)
                <button wire:click="selectUser({{ $user->id }})"
                    class="w-full text-left px-4 py-3 flex items-center gap-3 transition-all duration-150 rounded-xl mx-1
                        {{ $activeUserId === $user->id
                            ? 'bg-gradient-to-r from-violet-600/20 to-indigo-600/10 border border-violet-500/20'
                            : 'hover:bg-white/5 border border-transparent' }}"
                    style="width: calc(100% - 8px)">
                    <div class="relative shrink-0">
                        <div
                            class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm
                            {{ $activeUserId === $user->id
                                ? 'bg-gradient-to-br from-violet-500 to-indigo-600 shadow-lg shadow-violet-500/30'
                                : 'bg-white/10' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#0f0c29]"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p
                            class="text-sm font-medium truncate {{ $activeUserId === $user->id ? 'text-white' : 'text-white/70' }}">
                            {{ $user->userNickname?->nickname ?: $user->name }}
                        </p>
                        @if ($user->userNickname?->nickname)
                            <p class="text-xs text-white/25 truncate">{{ $user->name }}</p>
                        @else
                            <p class="text-xs text-white/25 truncate">Tap to chat</p>
                        @endif
                    </div>
                    @if ($activeUserId === $user->id)
                        <div class="w-1.5 h-1.5 rounded-full bg-violet-400 shrink-0"></div>
                    @endif
                </button>
            @empty
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-xs text-white/20">No other users yet</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Panel --}}
    <div class="flex-1 flex flex-col min-w-0">
        @if ($activeUser)

            {{-- Chat Header --}}
            <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center font-bold text-sm shadow-lg shadow-violet-500/20">
                            {{ strtoupper(substr($activeUser->userNickname?->nickname ?: $activeUser->name, 0, 1)) }}
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#0f0c29]"></span>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-white">
                            {{ $activeUser->userNickname?->nickname ?: $activeUser->name }}</p>
                        <p class="text-xs text-emerald-400">Online</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="openNicknameModal"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-violet-400 bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        {{ $activeUser->userNickname?->nickname ? 'Edit Nickname' : 'Set Nickname' }}
                    </button>
                    <button wire:click="toggleChatInfo"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium transition
                            {{ $showChatInfo
                                ? 'text-white bg-white/10 border border-white/20'
                                : 'text-white/40 bg-white/5 hover:bg-white/10 border border-white/10' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Chat Info
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                @forelse ($messages as $message)
                    @php $isMine = $message->user_id === Auth::id(); @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">

                        @if (!$isMine)
                            <div
                                class="w-7 h-7 rounded-xl bg-white/10 flex items-center justify-center text-xs font-bold shrink-0 mb-1">
                                {{ strtoupper(substr($message->user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="max-w-xs lg:max-w-sm">
                            <div
                                class="px-4 py-2.5 rounded-2xl text-sm break-words leading-relaxed
                                {{ $isMine
                                    ? 'bg-gradient-to-br from-violet-600 to-indigo-600 text-white rounded-br-sm shadow-lg shadow-violet-500/20'
                                    : 'bg-white/8 text-white/80 border border-white/10 rounded-bl-sm backdrop-blur-sm' }}">
                                {!! $message->body !!}
                            </div>
                            <p class="text-[10px] text-white/20 mt-1 {{ $isMine ? 'text-right' : '' }}">
                                {{ $message->created_at->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                        <div
                            class="w-16 h-16 rounded-3xl bg-gradient-to-br from-violet-500/20 to-indigo-500/20 border border-violet-500/20 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-white/40 text-sm font-medium">No messages yet</p>
                        <p class="text-white/20 text-xs mt-1">Say hi to
                            {{ $activeUser->userNickname?->nickname ?: $activeUser->name }}! 👋</p>
                    </div>
                @endforelse
            </div>

            {{-- Message Input --}}
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02] shrink-0">
                <form wire:submit="sendMessage" class="flex items-center gap-3">
                    <div
                        class="flex-1 flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-2.5 focus-within:border-violet-500/50 focus-within:bg-white/8 transition">
                        <input wire:model="newMessage" type="text" placeholder="Type a message..."
                            class="flex-1 bg-transparent text-sm text-white placeholder-white/20 focus:outline-none" />
                    </div>
                    <button type="submit"
                        class="w-11 h-11 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 flex items-center justify-center shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5 active:translate-y-0 shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        @else
            {{-- Empty state --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center px-8">
                <div
                    class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-500/20 to-indigo-500/20 border border-violet-500/20 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <p class="text-white/60 font-semibold text-lg">Your messages</p>
                <p class="text-white/25 text-sm mt-2 max-w-xs">Select a conversation from the sidebar to start chatting
                </p>
            </div>
        @endif
    </div>

    {{-- Chat Info Panel --}}
    @if ($showChatInfo && $activeUser)
        <div class="w-72 flex flex-col border-l border-white/5 bg-white/[0.03]">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <span class="font-semibold text-sm text-white">Chat Info</span>
                <button wire:click="toggleChatInfo"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-white/30 hover:text-white hover:bg-white/10 transition text-lg leading-none">
                    &times;
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-6">
                {{-- You --}}
                <div>
                    <p class="text-[10px] font-semibold text-white/25 uppercase tracking-widest mb-3">You</p>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/30 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/5"></div>

                {{-- Other user --}}
                <div>
                    <p class="text-[10px] font-semibold text-white/25 uppercase tracking-widest mb-3">
                        {{ $activeUser->userNickname?->nickname ?: $activeUser->name }}
                    </p>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($activeUser->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">
                                {{ $activeUser->userNickname?->nickname ?: $activeUser->name }}</p>
                            <p class="text-xs text-white/30 truncate">{{ $activeUser->email }}</p>
                        </div>
                    </div>
                    @if ($activeUser->userNickname?->nickname)
                        <div class="mt-2 px-3 py-2 rounded-xl bg-violet-500/10 border border-violet-500/20">
                            <p class="text-xs text-violet-300">
                                Nickname: <span class="font-semibold">{{ $activeUser->userNickname->nickname }}</span>
                                <span class="text-violet-400/50 ml-1">· only you see this</span>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-white/5"></div>

                {{-- Conversation between --}}
                <div>
                    <p class="text-[10px] font-semibold text-white/25 uppercase tracking-widest mb-3">Conversation</p>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                            <span class="text-white/20 text-xs">&amp;</span>
                            <span class="text-sm font-semibold text-white">{{ $activeUser->name }}</span>
                        </div>
                        @if ($activeUser->userNickname?->nickname)
                            <p class="text-xs text-white/30 mt-1.5">
                                You call them
                                <span
                                    class="text-violet-400 font-medium">{{ $activeUser->userNickname->nickname }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Nickname Modal --}}
    @if ($showNicknameModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-[#1a1535] border border-white/10 rounded-3xl p-6 w-80 shadow-2xl">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="w-9 h-9 rounded-xl bg-violet-500/20 border border-violet-500/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Set Nickname</h2>
                        <p class="text-xs text-white/30">Only visible to you</p>
                    </div>
                </div>
                <input wire:model="nicknameInput" type="text" placeholder="Enter a nickname..."
                    class="w-full bg-white/5 border border-white/10 text-white placeholder-white/20 rounded-xl px-4 py-2.5 text-sm mb-4 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition" />
                <div class="flex gap-2">
                    <button wire:click="cancelNickname"
                        class="flex-1 py-2.5 rounded-xl text-sm text-white/40 bg-white/5 hover:bg-white/10 border border-white/10 transition">
                        Cancel
                    </button>
                    <button wire:click="saveNickname"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 transition">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
