@extends('dashboard.layout')

@section('pageKicker', 'Account')
@section('pageTitle', 'My Profile')
@section('pageSubtitle', 'Update your account details in the same teal-and-gold dashboard style used across the fleet system.')

@section('content')
    <section class="summary-grid">
        <article class="highlight-card">
            <h3>Account Overview</h3>
            <p class="page-subtitle">Your profile page now matches the dashboard theme instead of dropping back to the default Jetstream screen.</p>
            <div class="list-stack">
                <div class="list-item">
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <div class="muted">{{ $user->email }}</div>
                    </div>
                    <span class="chip success">Signed in</span>
                </div>
            </div>
        </article>

        <article class="card">
            <h3>Traffic Lights</h3>
            <div class="traffic-lights topbar-lights" style="margin-top: 18px;">
                <span class="traffic-dot stop"></span>
                <span class="traffic-dot wait"></span>
                <span class="traffic-dot go"></span>
            </div>
            <p class="metric-meta">The original traffic lights are now part of the profile screen too, so the whole dashboard feels consistent.</p>
        </article>
    </section>

    <section class="panel-stack" style="margin-top: 24px;">
        <article class="card">
            <h3>Profile Information</h3>
            <p class="metric-meta">Update your name and email address.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Name</label>
                        <input class="input" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        @error('name')
                            <div class="muted" style="color: var(--danger);">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input class="input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="muted" style="color: var(--danger);">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">
                        <i class='bx bxs-save'></i>
                        <span>Save Profile</span>
                    </button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Update Password</h3>
            <p class="metric-meta">Use a strong password to keep your account secure.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="field">
                        <label for="current_password">Current Password</label>
                        <input class="input" id="current_password" name="current_password" type="password" autocomplete="current-password">
                        @if ($errors->updatePassword->has('current_password'))
                            <div class="muted" style="color: var(--danger);">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="password">New Password</label>
                        <input class="input" id="password" name="password" type="password" autocomplete="new-password">
                        @if ($errors->updatePassword->has('password'))
                            <div class="muted" style="color: var(--danger);">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <div class="muted" style="color: var(--danger);">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">
                        <i class='bx bxs-lock-alt'></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Delete Account</h3>
            <p class="metric-meta">This action is permanent. Enter your current password to confirm.</p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="form-grid">
                    <div class="field">
                        <label for="delete_password">Current Password</label>
                        <input class="input" id="delete_password" name="password" type="password" autocomplete="current-password">
                        @if ($errors->userDeletion->has('password'))
                            <div class="muted" style="color: var(--danger);">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button" type="submit" style="background: #b91c1c; color: #fff;">
                        <i class='bx bxs-trash'></i>
                        <span>Delete Account</span>
                    </button>
                </div>
            </form>
        </article>
    </section>
@endsection
