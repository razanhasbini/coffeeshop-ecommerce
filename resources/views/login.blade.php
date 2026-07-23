@extends('layout')

@section('title', 'Sign In — CoffeeShop')

@section('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 72px);
        background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee-mid) 100%);
        display: flex;
        align-items: center;
        padding: 3rem 1rem;
    }
    .auth-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(28,15,7,.25);
        overflow: hidden;
        width: 100%;
        max-width: 440px;
        margin: 0 auto;
    }
    .auth-card-header {
        background: linear-gradient(135deg, var(--coffee-mid), var(--coffee-dark));
        padding: 2.2rem 2rem 1.8rem;
        text-align: center;
    }
    .auth-card-header .logo { font-size: 2rem; margin-bottom: .5rem; }
    .auth-card-header h2 { color: #fff; font-size: 1.5rem; margin: 0; }
    .auth-card-header p { color: rgba(255,255,255,.6); font-size: .85rem; margin: .3rem 0 0; }
    .auth-card-body { padding: 2rem; }

    .auth-tabs {
        display: flex;
        background: var(--warm-gray);
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 1.8rem;
    }
    .auth-tab {
        flex: 1;
        text-align: center;
        padding: .55rem;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        color: var(--text-muted);
        transition: var(--transition);
        user-select: none;
    }
    .auth-tab.active {
        background: #fff;
        color: var(--coffee-dark);
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card-header">
                <div class="logo">☕</div>
                <h2 id="form-title">Welcome Back</h2>
                <p id="form-subtitle">Sign in to your account</p>
            </div>
            <div class="auth-card-body">

                @if ($errors->any())
                <div class="alert alert-danger rounded-3 py-2 mb-3" style="font-size:.85rem;">
                    @foreach ($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success rounded-3 py-2 mb-3" style="font-size:.85rem;">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
                @endif

                <div class="auth-tabs mb-0">
                    <div class="auth-tab active" id="tab-login" onclick="switchTab('login')">Sign In</div>
                    <div class="auth-tab" id="tab-register" onclick="switchTab('register')">Create Account</div>
                </div>

                <div id="form-body" style="margin-top:1.5rem;">
                    {{-- Login --}}
                    <form id="loginForm" method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-coffee w-100">Sign In</button>
                    </form>

                    {{-- Register --}}
                    <form id="registerForm" method="POST" action="{{ url('/register') }}" style="display:none;">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-coffee w-100">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function switchTab(tab) {
    const isLogin = tab === 'login';
    document.getElementById('loginForm').style.display    = isLogin ? 'block' : 'none';
    document.getElementById('registerForm').style.display = isLogin ? 'none'  : 'block';
    document.getElementById('tab-login').classList.toggle('active', isLogin);
    document.getElementById('tab-register').classList.toggle('active', !isLogin);
    document.getElementById('form-title').textContent    = isLogin ? 'Welcome Back'       : 'Create Account';
    document.getElementById('form-subtitle').textContent = isLogin ? 'Sign in to your account' : 'Join our coffee community';
}
</script>
@endsection
