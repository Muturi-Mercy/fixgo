@extends('layouts.app')

@section('title', 'Chat')
@section('page-title', 'Chat with Customer')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link active">
        <i class="fas fa-bell"></i> Service Requests
    </a>
    <a href="{{ route('mechanic.my-jobs') }}" class="nav-link">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="{{ route('mechanic.earnings') }}" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link {{ request()->routeIs('mechanic.reviews') ? 'active' : '' }}">
    <i class="fas fa-star"></i> Reviews
    @php
        $newReviews = \App\Models\RatingReview::where('mechanic_id', auth()->user()->mechanic->id ?? 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    @endphp
    @if($newReviews > 0)
        <span class="nav-badge nav-badge-orange">{{ $newReviews }}</span>
    @endif
    </a>
    <a href="{{ route('mechanic.notifications') }}" class="nav-link">
    <i class="fas fa-bell"></i> Notifications
    @if(auth()->user()->unreadNotifications->count())
        <span class="nav-badge" id="sidebarNotifBadge">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
    </a>
    <a href="{{ route('mechanic.settings') }}" class="nav-link">
        <i class="fas fa-cog"></i> Settings
    </a>
    <a href="{{ route('mechanic.profile') }}" class="nav-link">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('logout') }}" class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
@endsection

@section('content')

<div class="mb-3">
    <a href="{{ route('mechanic.service-requests') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Service Requests
    </a>
</div>

<div class="row g-4">

    {{-- Chat Window --}}
    <div class="col-lg-8">
        <div class="fixgo-card" style="height:600px;display:flex;flex-direction:column">

            {{-- Chat Header --}}
            <div style="padding:16px 20px;border-bottom:1px solid #f0f4ff;
                        display:flex;align-items:center;gap:12px">
                <div class="nav-user-avatar" style="width:44px;height:44px;font-size:18px">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p style="font-weight:700;color:#1a3c6e;margin:0;font-size:15px">
                        {{ $request->user->name }}
                    </p>
                    <p style="font-size:12px;color:#6b7280;margin:0">
                        <i class="fas fa-car me-1"></i> Customer
                    </p>
                </div>
                <div class="ms-auto">
                    <span class="status-badge badge-{{ str_replace('_','-',$request->status) }}">
                        {{ ucwords(str_replace('_',' ',$request->status)) }}
                    </span>
                </div>
            </div>

            {{-- Messages --}}
            <div id="chatMessages"
                 style="flex:1;overflow-y:auto;padding:20px;
                        display:flex;flex-direction:column;gap:12px">
                @foreach($messages as $msg)
                <div class="chat-bubble {{ $msg->sender_id === auth()->id() ? 'mine' : 'theirs' }}">
                    <div class="bubble-content">{{ $msg->message }}</div>
                    <div class="bubble-time">
                        {{ $msg->created_at->format('h:i A') }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Input --}}
            <div style="padding:16px 20px;border-top:1px solid #f0f4ff">
                <div class="d-flex gap-2">
                    <input type="text" id="messageInput"
                           class="form-control"
                           placeholder="Type a message..."
                           onkeydown="if(event.key==='Enter') sendMessage()">
                    <button onclick="sendMessage()"
                            class="btn btn-fixgo"
                            style="width:auto;padding:10px 20px">
                       <span style="color: #f97316" ><i class="fas fa-paper-plane"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Info --}}
    <div class="col-lg-4">
        <div class="fixgo-card mb-3">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Request Info</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label">Service</span>
                    <span class="confirm-value">
                        {{ $request->serviceCategory->name ?? 'N/A' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Request #</span>
                    <span class="confirm-value">{{ $request->request_number }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Problem</span>
                    <span class="confirm-value">
                        {{ \Illuminate\Support\Str::limit($request->problem_description, 40) }}
                    </span>
                </div>
                @if($request->price)
                <div class="confirm-row">
                    <span class="confirm-label">Charge</span>
                    <span class="confirm-value" style="color:#10b981;font-weight:700">
                        KSh {{ number_format($request->price) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-user me-2 text-primary"></i>Customer</h6>
            </div>
            <div class="fixgo-card-body text-center">
                <div class="nav-user-avatar mx-auto mb-2"
                     style="width:56px;height:56px;font-size:22px">
                    <i class="fas fa-user"></i>
                </div>
                <p style="font-weight:700;color:#1a3c6e;margin:0">
                    {{ $request->user->name }}
                </p>
                <p class="text-muted mb-3" style="font-size:13px">
                    {{ $request->user->phone ?? 'No phone' }}
                </p>
                <a href="tel:{{ $request->user->phone }}"
                   class="btn btn-outline-success btn-sm w-100 mb-2">
                    <i class="fas fa-phone me-1"></i> Call Customer
                </a>
                <a href="{{ route('mechanic.view-driver', $request->user_id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-user me-1"></i> View Profile
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.chat-bubble {
    display: flex;
    flex-direction: column;
    max-width: 70%;
}
.chat-bubble.mine { align-self:flex-end; align-items:flex-end; }
.chat-bubble.theirs { align-self:flex-start; align-items:flex-start; }
.bubble-content {
    padding: 10px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.5;
    word-break: break-word;
}
.mine .bubble-content {
    background: linear-gradient(135deg, #1a3c6e, #3b82f6);
    color: white;
    border-radius: 18px 18px 4px 18px;
}
.theirs .bubble-content {
    background: #f0f4ff;
    color: #1a3c6e;
    border-radius: 18px 18px 18px 4px;
}
.bubble-time { font-size:11px; color:#9ca3af; margin-top:4px; padding:0 4px; }
</style>
@endpush

@push('scripts')
<script>
const requestId = {{ $request->id }};
const chatContainer = document.getElementById('chatMessages');

function scrollToBottom() {
    chatContainer.scrollTop = chatContainer.scrollHeight;
}
scrollToBottom();

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    if (!message) return;
    input.value = '';

    fetch(`/mechanic/chat/${requestId}/send`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message })
    })
    .then(() => loadMessages());
}

function loadMessages() {
    fetch(`/mechanic/chat/${requestId}/messages`)
        .then(r => r.json())
        .then(messages => {
            chatContainer.innerHTML = '';
            messages.forEach(msg => {
                const div = document.createElement('div');
                div.className = `chat-bubble ${msg.is_mine ? 'mine' : 'theirs'}`;
                div.innerHTML = `
                    <div class="bubble-content">${msg.message}</div>
                    <div class="bubble-time">${msg.time}</div>
                `;
                chatContainer.appendChild(div);
            });
            scrollToBottom();
        });
}

setInterval(loadMessages, 5000);
</script>
@endpush