
<form method="POST" action="{{ route('auth.logout') }}" id="logout-form">
    @csrf
    <button type="submit" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit(); window.location.href='/';">
        Logout
    </button>
</form>
